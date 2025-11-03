<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ArrayExport implements FromArray, WithHeadings, WithTitle, WithEvents
{
    protected array $data;
    protected string $reportTitle;

    public function __construct(array $data, string $reportTitle = 'WO Progress Report')
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
            'WO No',
            'Item Code',
            'Item Type',
            'Seq No',
            'Process Code',
            'Process Name',
            'Input Qty',
            'NG Qty',
            'Output Qty',
            'Machine Code',
            'Employee Name',
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

                // Insert title at the top
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', $this->reportTitle);

                // Style the title
                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Style header row
                $sheet->getStyle('A3:K3')->getFont()->setBold(true);
                $sheet->getStyle('A3:K3')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
