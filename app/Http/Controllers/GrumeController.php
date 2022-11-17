<?php

namespace App\Http\Controllers;

use App\Models\Grume;
use Illuminate\Http\Request;

class GrumeController extends Controller
{
    public function index(Request $request)
    {
        //search
        if ($request->search != null) {
            $query = Grume::where('number', 'LIKE', $request->search . '%');
        } else {
            //filter
            $query = Grume::filter($request->filters);
            //sort
            $column = $request->sort['column'];
            $order = $request->sort['order'] == 1 ? 'asc' : 'desc';
            if ($column && $order) {
                $query = $query->orderBy($column, $order);
            }
        }

        $records = $query->get()->values()->all();
        $totalRecords = count($records);

        //paginate
        $from = $request->paginate["from"];
        $to  = $request->paginate["to"];

        $records = array_slice($records, $from, $to - $from + 1);

        return [
            'records' => $records,
            'totalRecords' => $totalRecords
        ];
    }

    public function show($id){
        return Grume::find($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric',
            'length' => 'required|numeric',
            'diameter' => 'required|numeric'
        ]);
        $grume = Grume::create($request->all());
        return $grume;
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

        $request->validate([
            'length' => 'required|numeric',
            'diameter' => 'required|numeric'
        ]);

        $grume = Grume::find($id);
        if ($grume) {
            $grume->length = $request->length;
            $grume->diameter = $request->diameter;
            $grume->save();
        }
        return $grume;
    }

    public function destroy(Request $request)
    {
        foreach ($request->records as $record) {
            $grume = Grume::find($record['number']);
            if ($grume) $grume->delete();
        }
    }

    // public function upload(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx'
    //     ]);
    //     $file_path = $request->file->getPathName();

    //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //     $reader->setReadDataOnly(true);
    //     $spreadsheet = $reader->load($file_path);
    //     $cells = $spreadsheet->getSheet(0)->getCellCollection();
    //     $coordinates = array_values(array_filter($cells->getSortedCoordinates(), function ($coord) use ($cells) {
    //         return $cells->get($coord)->getValue() != null;
    //     }));

    //     $perRow = 3;
    //     $row_count = ceil(count($coordinates) / $perRow);

    //     $table = [];

    //     for ($i = 0; $i < $row_count; $i++) {
    //         $row = [];
    //         for ($j = 0; $j < $perRow; $j++) {
    //             $coord = $coordinates[$i * $perRow + $j];
    //             $row[] = $cells->get($coord)->getValue();
    //         }
    //         $table[] = $row;
    //     }



    //     for ($i = 1; $i < count($table); $i++) {
    //         $row = $table[$i];
    //         $grume = new Grume([
    //             'number' => $row[0],
    //             'length' => $row[1],
    //             'diameter' => $row[2],
    //             'container_number' => $row[3] ?? null
    //         ]);
    //         $grume->save();
    //     }
    // }
    public function download(Request $request)
    {
        $request->validate([
            'records'=>'required|array|min:1',
        ]);


        //download selected records
        $record_numbers = $request->records;
        if ($record_numbers) {
            $grumes = Grume::whereIn('number', $record_numbers)->get();
        }

        $file = Grume::createExcel($grumes);

        return response()->download($file);
    }
}
