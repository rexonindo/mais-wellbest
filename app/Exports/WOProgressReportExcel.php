<?php


namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Carbon;

class WOProgressReportExcel implements FromCollection, WithHeadings, WithTitle, WithEvents, WithMapping
{
    protected Collection $data;
    protected string $reportTitle;

    public function __construct(Collection $data, string $reportTitle = 'WO Progress Report')
    {
        $this->data = $data;
        $this->reportTitle = $reportTitle;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'WO No',
            'Part No',
            'Part Type',
            'Seq No',
            'Process Code',
            'Process Name',
            'WO Qty',
            'Cavity',
            'Rework Qty',
            'NG Qty',
            'OK Qty',
            'Total Qty',
            'Total Qty (Shoot)',
            'On-Hand Qty',
            'Machine Code',
            'Employee Name',
            'Start',
            'Finish',
        ];
    }

    public function map($row): array
    {
        $toExcelDate = function ($value) {
            if (empty($value)) {
                return null;
            }

            return ExcelDate::dateTimeToExcel(
                $value instanceof \DateTimeInterface
                    ? $value
                    : Carbon::parse($value)
            );
        };

        return [
            $row->wo_no, //A
            $row->itm_cd, //B
            $row->itm_type, //C
            $row->seq_no, //D
            $row->proc_cd, //E 
            $row->proc_nm, //F
            $row->wo_qty, //G
            $row->cav, //H
            $row->rwk_qty, //I
            $row->ng_qty, //J
            $row->out_qty, //K
            $row->ttl_qty, //L
            $row->ttl_qty_shoot, //M
            $row->onhand_qty, //N
            $row->mchn_cd, //O
            $row->emp_nm, //P
            $toExcelDate($row->start_time), //Q
            $toExcelDate($row->end_time), //R
        ];
    }

    public function title(): string
    {
        return 'WO Progress Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {                
                
                $sheet = $event->sheet;
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells('A1:S1');
                $sheet->setCellValue('A1', $this->reportTitle);

                $sheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(14);

                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal('left');
                    
                $sheet->getStyle('A3:S3')->getFont()->setBold(true);
                $sheet->getStyle('A3:S3')->getAlignment()->setHorizontal('center');

                $rowCount = $this->data->count() + 4; // header rows

                foreach (range('A', 'S') as $col) {
                    $event->sheet->getDelegate()
                        ->getColumnDimension($col)
                        ->setAutoSize(true);
                }                   

                $textColumns = ['A','B','C','D','E','F','O','P'];
                foreach ($textColumns as $column) {
                    $sheet->getStyle("{$column}4:{$column}{$rowCount}")
                        ->getAlignment()
                        ->setHorizontal('left');
                }

                $numberColumns = ['G','H','I','J','K','L','M','N'];
                foreach ($numberColumns as $column) {
                    $sheet->getStyle("{$column}4:{$column}{$rowCount}")
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_NUMBER);
                }

                $dateColumns = ['Q','R'];
                foreach ($dateColumns as $column) {
                    $sheet->getStyle("{$column}4:{$column}{$rowCount}")
                        ->getNumberFormat()
                        ->setFormatCode('dd-mmm-yyyy HH:mm:ss');
                }
            },
        ];
    }
}
