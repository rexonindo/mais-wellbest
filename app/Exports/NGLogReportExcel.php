<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class NGLogReportExcel implements FromArray, WithHeadings, WithTitle, WithEvents
{
    protected array $data;
    protected string $reportTitle;

    public function __construct(array $data, string $reportTitle = 'NG Log Report')
    {
        $this->data = $data;
        $this->reportTitle = $reportTitle;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Process Date', 
            'Part No', 
            'Part Type', 
            'Process Name', 
            'NG Name', 
            'NG Qty', 
            'Operator', 
            'Machine',
        ];
    }

    public function title(): string
    {
        return 'NG Log Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Insert title at the top
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells('A1:L1');
                $sheet->setCellValue('A1', $this->reportTitle);

                // Style the title
                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Style header row
                $sheet->getStyle('A3:L3')->getFont()->setBold(true);
                $sheet->getStyle('A3:L3')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
