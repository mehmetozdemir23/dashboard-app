<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grume extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'length', 'diameter', 'container_number'];
    protected $primaryKey = 'number';
    public $incrementing = false;



    static function filter($filters)
    {

        //$search = $filters['search'] ?? '';

        $whereCondition = [];
        foreach ($filters as $column => $bounds) {

            $minValue = $bounds['min'];
            $maxValue = $bounds['max'];
            $whereCondition[] = [$column, '<=', $maxValue];
            $whereCondition[] = [$column, '>=', $minValue];
        }


        return Grume::where($whereCondition);
    }

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_number');
    }

    static function readFromExcel(Spreadsheet $spreadsheet)
    {
        dd($spreadsheet);
    }

    static function createExcel($data)
    {
        //fill the spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //grumes listing section
        $row_index = 3;
        $sheet->setCellValue('A' . $row_index, 'N°');
        $sheet->setCellValue('B' . $row_index, 'Longueur');
        $sheet->setCellValue('C' . $row_index, 'Diamètre');
        $sheet->setCellValue('D' . $row_index, 'Volume');
        $row_index++;
        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row_index, $d->number);
            $sheet->setCellValue('B' . $row_index, $d->length);
            $sheet->setCellValue('C' . $row_index, $d->diameter);
            $sheet->setCellValue('D' . $row_index, $d->volume);
            $row_index++;
        }


        $writer = new Xlsx($spreadsheet);
        $path = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($path);
        return $path;
    }
}
