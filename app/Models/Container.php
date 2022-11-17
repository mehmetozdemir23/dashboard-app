<?php

namespace App\Models;

use ZipArchive;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Container extends Model
{
    use HasFactory;


    protected $guarded = [];
    protected $primaryKey = 'number';
    public $incrementing = false;

    public function grumes()
    {
        return $this->hasMany(Grume::class, 'container_number');
    }
    static function filter($filters)
    {

        $search = $filters['search'] ?? '';
        $ranges = $filters['ranges'] ?? [];


        $whereCondition = [['number', 'LIKE', $search . '%']];

        foreach ($ranges as $range) {
            $field = $range['field'];
            $minValue = $range['min'];
            $maxValue = $range['max'];
            $whereCondition[] = [$field, '<=', $maxValue];
            $whereCondition[] = [$field, '>=', $minValue];
        }



        return self::where($whereCondition);
    }

    static function createExcel($containers)
    {
        //create a spreadsheet and remove existing worksheet from it
        $spreadsheet = new Spreadsheet();
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getActiveSheet()
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);

        //add a worksheet to the spreadsheet for each container
        foreach ($containers as $container) {
            $sheet = new Worksheet($spreadsheet, 'container_' . $container->number);
            $spreadsheet->addSheet($sheet);


            //container information section
            $row_index = 3;
            $sheet->setCellValue('A' . $row_index, 'N°');
            $sheet->setCellValue('B' . $row_index, $container->number);
            $row_index++;
            $sheet->setCellValue('A' . $row_index, 'Tare');
            $sheet->setCellValue('B' . $row_index, $container->tare);
            $row_index++;
            $sheet->setCellValue('A' . $row_index, 'Seal');
            $sheet->setCellValue('B' . $row_index, $container->seal);
            $row_index++;
            $sheet->setCellValue('A' . $row_index, 'Quantity');
            $sheet->setCellValue('B' . $row_index, count($container->grumes));
            $row_index += 2;

            //grumes listing section
            $sheet->setCellValue('A' . $row_index, 'N°');
            $sheet->setCellValue('B' . $row_index, 'Longueur');
            $sheet->setCellValue('C' . $row_index, 'Diamètre');
            $sheet->setCellValue('D' . $row_index, 'Volume');
            $row_index++;
            foreach ($container->grumes as $grume) {
                $sheet->setCellValue('A' . $row_index, $grume->number);
                $sheet->setCellValue('B' . $row_index, $grume->length);
                $sheet->setCellValue('C' . $row_index, $grume->diameter);
                $sheet->setCellValue('D' . $row_index, $grume->volume);
                $row_index++;
            }
            //total volume and percentage of grumes with a diameter > 40
            $total_volume = 0;
            $count = 0;
            foreach ($container->grumes as $grume) {

                if ($grume->diameter > 40) {
                    $total_volume += $grume->volume;
                    $count++;
                }
            }
            $percentage = $count / count($container->grumes) * 100;
            $row_index += 2;
            $sheet->setCellValue('A' . $row_index, 'Percentage > 40 diam');
            $sheet->setCellValue('B' . $row_index, $percentage);
            $row_index++;
            $sheet->setCellValue('A' . $row_index, 'Volume > 40 diam');
            $sheet->setCellValue('B' . $row_index, $total_volume);
        }


        $writer = new Xlsx($spreadsheet);
        $path = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($path);
        return $path;
    }
}
