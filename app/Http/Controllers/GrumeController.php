<?php

namespace App\Http\Controllers;

use App\Http\Resources\GrumeResource;
use App\Models\Grume;
use Illuminate\Http\Request;

class GrumeController extends Controller
{
    public function index(Request $request)
    {
        //filter
        $query = Grume::filter($request->filters);

        //sort
        $column = $request->sort['column'];
        $order = $request->sort['order'] == 1 ? 'asc' : 'desc';
        if ($column && $order) {
            $query = $query->orderBy($column, $order);
        }

        //paginate
        $from = $request->paginate["from"];
        $to  = $request->paginate["to"];
        // if ($from && $to) {
        //     $query = $query->skip($from)->take($to - $from + 1);
        // }
        $records = $query->get()->values()->all();
        $totalRecords = count($records);

        $records = array_slice($records, $from, $to - $from + 1);
        return [
            'records' => $records,
            'totalRecords' => $totalRecords
        ];
        /*
        //filters
        $grumes = Grume::filter($request->filters ?? []);

        //sort
        $column = $request->sort['column'] ?? '';
        $order = $request->sort['order'] ?? '';
        if ($column && $order) {

            if ($order == "asc")
                $grumes = $grumes->sortBy(fn ($a, $b) => $a[$column]);
            else if ($order == "desc")
                $grumes = $grumes->sortByDesc(fn ($a, $b) => $a[$column]);
        }

        //pagination
        $start = $request->pagination['start'];
        $end = $request->pagination['end'];


        $totalRecords = count($grumes);
        $grumes = $grumes->slice($start, $end - $start + 1);


        return [
            'totalRecords' => $totalRecords,
            'records' => GrumeResource::collection($grumes)
        ];*/
    }
    /*
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|regex:/^grumes$/',
            'file' => 'required|mimes:xlsx'
        ]);
        $type = $request->type;
        $file = $request->file;

        $file_path = $file->getPathName();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file_path);
        $cells = $spreadsheet->getSheet(0)->getCellCollection();
        $coordinates = $cells->getSortedCoordinates();
        $perRow = 4;
        $row_count = ceil(count($coordinates) / $perRow);
        $table = [];

        for ($i = 0; $i < $row_count; $i++) {
            $row = [];
            for ($j = 0; $j < $perRow; $j++) {
                $coord = $coordinates[$i * $perRow + $j];
                $row[] = $cells->get($coord)->getValue();
            }
            $table[] = $row;
        }

        for ($i = 1; $i < count($table); $i++) {
            $row = $table[$i];
            $grume = new Grume([
                'number' => $row[0],
                'length' => $row[1],
                'diameter' => $row[2]
            ]);
            $grume->save();
        }
    }
*/
    public function update(Request $request, int $id)
    {

        $grume = Grume::find($id);

        $grume->length = $request->length;
        $grume->diameter = $request->diameter;
        $grume->save();
        return $grume;
    }

    public function destroy(int $id)
    {
        $grume = Grume::find($id);
        $grume->delete();
        return $grume;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);
        $file_path = $request->file->getPathName();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file_path);
        $cells = $spreadsheet->getSheet(0)->getCellCollection();
        $coordinates = array_values(array_filter($cells->getSortedCoordinates(), function ($coord) use ($cells) {
            return $cells->get($coord)->getValue() != null;
        }));

        $perRow = 3;
        $row_count = ceil(count($coordinates) / $perRow);

        $table = [];

        for ($i = 0; $i < $row_count; $i++) {
            $row = [];
            for ($j = 0; $j < $perRow; $j++) {
                $coord = $coordinates[$i * $perRow + $j];
                $row[] = $cells->get($coord)->getValue();
            }
            $table[] = $row;
        }



        for ($i = 1; $i < count($table); $i++) {
            $row = $table[$i];
            $grume = new Grume([
                'number' => $row[0],
                'length' => $row[1],
                'diameter' => $row[2],
                'container_number'=>$row[3] ?? null
            ]);
            $grume->save();
        }
    }
    public function download(Request $request)
    {
        //filter
        $grumes = Grume::filter($request->filters)->get();

        $file = Grume::createExcel($grumes);

        return response()->download($file);
    }
}
