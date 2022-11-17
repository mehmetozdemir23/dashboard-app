<?php

namespace App\Http\Controllers;

use App\Models\Grume;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ContainerResource;
use Illuminate\Validation\Rule;

class ContainerController extends Controller
{
    public function index(Request $request)
    {
        //search
        if ($request->search != null) {
            $query = Container::where('number', 'LIKE', $request->search . '%');
        } else {
            //filter
            $query = Container::filter($request->filters);
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
        return Container::find($id);
    }


    public function store(Request $request){
        $request->validate([
            'number'=>'required|unique:containers,number',
            'tare'=>'required|numeric',
            'seal'=>'required|unique:containers,seal',
            'grumes'=>'array|min:1',
            // 'grumes.*.number'=> Rule::notIn(Container::with('grumes:container_number,number')->get()->map(fn($cont)=>$cont->grumes->all())->flatten()->all())
        ]);
        $container = Container::create($request->only('number','tare','seal'));
        foreach ($request->grumes as $grume_number) {
            $grume = Grume::find($grume_number);
            if($grume){

                $grume->container_number = $container->number;
                $grume->save();
            }
        }
        return $container;
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required|regex:/^containers$/',
    //         'file' => 'required|mimes:xlsx'
    //     ]);
    //     $type = $request->type;
    //     $file = $request->file;

    //     $file_path = $file->getPathName();

    //     $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    //     $reader->setReadDataOnly(true);
    //     $spreadsheet = $reader->load($file_path);
    //     $cells = $spreadsheet->getSheet(0)->getCellCollection();
    //     $coordinates = $cells->getSortedCoordinates();
    //     $perRow = 4;
    //     $row_count = ceil(count($coordinates) / $perRow);
    //     $container_props = array_map(function ($coord) use ($cells) {
    //         return $cells->get($coord)->getValue();
    //     }, array_slice($coordinates, 5, 3));
    //     $container = new Container([
    //         'number' => $container_props[0],
    //         'tare' => $container_props[1],
    //         'seal' => $container_props[2]
    //     ]);
    //     $container->save();

    //     $coordinates = array_filter($cells->getCoordinates(), function ($coord) use ($cells) {
    //         return $cells->get($coord)->getValue();
    //     });
    //     $highest_column_name = max($coordinates)[0];
    //     $coordinates = array_values(array_filter($coordinates, function ($coord) use ($highest_column_name) {
    //         return $coord[0] == $highest_column_name;
    //     }));

    //     for ($i = 1; $i < count($coordinates); $i++) {
    //         $grume_number = $cells->get($coordinates[$i])->getValue();
    //         $grume = Grume::find($grume_number);
    //         if (!$grume->container_number)
    //             $grume->container_number = $container->number;

    //         $grume->save();
    //     }
    // }

    public function update(Request $request, $id)
    {

        $container = Container::find($id);

        $container->tare = $request->tare;
        $container->seal = $request->seal;
        $container->save();
        return $container;
    }

    public function destroy(Request $request)
    {
        foreach ($request->records as $record) {
            $container = Container::find($record['number']);
            $container->delete();
        }
    }

    public function download(Request $request)
    {
        //download selected records
        if ($request->records) {
            $record_numbers = array_map(fn ($record) => $record['number'], $request->records);
            $containers = Container::whereIn('number', $record_numbers)->get();
        } else {
            //download filtered records
            $containers = Container::filter($request->filters)->get();
        }

        $file = Container::createExcel($containers);
        return response()->download($file);

    }


    public function exportAsExcel(Request $request)
    {
        $containers = array_map(function ($number) {
            return Container::find($number);
        }, $request->numbers);

        $archive = Container::createExcel($containers);
        return response()->download($archive);
    }
}
