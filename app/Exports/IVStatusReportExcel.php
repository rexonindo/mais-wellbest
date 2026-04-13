<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class IVStatusReportExcel implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    protected Collection $data;
    protected string $reportTitle;

    public function __construct(Collection $data, string $reportTitle = 'Inventory Status Report')
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
            'Part No',
            'WIP Code',
            'Quantity',
        ];
    }
    public function title(): string
    {
        return 'Inventory Status Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Insert title rows
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', $this->reportTitle);

                // Title style
                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Header style
                $sheet->getStyle('A3:C3')->getFont()->setBold(true);
                $sheet->getStyle('A3:C3')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
