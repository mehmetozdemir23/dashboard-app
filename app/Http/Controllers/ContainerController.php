<?php

namespace App\Http\Controllers;

use App\Models\Grume;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ContainerResource;

class ContainerController extends Controller
{
    public function index(Request $request)
    {

        //filters
        $containers = Container::filter($request->filters ?? []);

        //sort
        $column = $request->sort['column'] ?? '';
        $order = $request->sort['order'] ?? '';
        if ($column && $order) {
            if ($order == "asc")
                $containers = $containers->sortBy(fn ($a, $b) => $a[$column]);
            else if ($order == "desc")
                $containers = $containers->sortByDesc(fn ($a, $b) => $a[$column]);
        }

        //pagination
        $start = $request->pagination['start'];
        $end = $request->pagination['end'];


        $totalRecords = count($containers);
        $containers = $containers->slice($start, $end - $start + 1);


        return [
            'totalRecords' => $totalRecords,
            'records' => ContainerResource::collection($containers)
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|regex:/^containers$/',
            'file' => 'required|mimes:xlsx'
        ]);
        $type = $request->type;
        $file = $request->file;

        $file_path = $file->getPathName();

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file_path);
        $cells = $spreadsheet->getSheet(0)->getCellCollection();
        $coordinates = $cells->getSortedCoordinates();
        $perRow = 4;
        $row_count = ceil(count($coordinates) / $perRow);
        $container_props = array_map(function ($coord) use ($cells) {
            return $cells->get($coord)->getValue();
        }, array_slice($coordinates, 5, 3));
        $container = new Container([
            'number' => $container_props[0],
            'tare' => $container_props[1],
            'seal' => $container_props[2]
        ]);
        $container->save();

        $coordinates = array_filter($cells->getCoordinates(), function ($coord) use ($cells) {
            return $cells->get($coord)->getValue();
        });
        $highest_column_name = max($coordinates)[0];
        $coordinates = array_values(array_filter($coordinates, function ($coord) use ($highest_column_name) {
            return $coord[0] == $highest_column_name;
        }));

        for ($i = 1; $i < count($coordinates); $i++) {
            $grume_number = $cells->get($coordinates[$i])->getValue();
            $grume = Grume::find($grume_number);
            if (!$grume->container_number)
                $grume->container_number = $container->number;

            $grume->save();
        }
    }

    public function update(Request $request)
    {
        $container = Container::find($request->number);
        $container->tare = $request->tare;
        $container->save();
    }

    public function destroy(Request $request)
    {
        $container = Container::find($request->number);
        $container->delete();
        return $container;
    }

    public function exportAsExcel(Request $request){
        $containers = array_map(function($number){
            return Container::find($number);
        },$request->numbers);

        $archive = Container::createExcel($containers);
        return response()->download($archive);

    }
}
