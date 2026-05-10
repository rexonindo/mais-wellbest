<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RWKStatusPivotReportExcel implements
    FromCollection,
    WithHeadings,
    WithTitle,
    WithEvents
{
    protected Collection $data;
    protected string $reportTitle;
    protected array $columns = [];

    public function __construct(Collection $data, string $reportTitle = 'RWK Status Pivot By Process')
    {
        $this->data = $data;
        $this->reportTitle = $reportTitle;

        // Detect ONLY real columns
        if ($data->isNotEmpty()) {
            $this->columns = array_keys(
                $data->first()->getAttributes()
            );
        }
    }

    /* ---------------------------------
       Data
       --------------------------------- */
    public function collection(): Collection
    {
        return $this->data->map(function ($row) {

            $row = $row->getAttributes();

            foreach ($row as $key => $value) {
                if (is_numeric($value)) {
                    $row[$key] = round((float) $value, 0);
                }
            }

            return $row;
        });
    }

    /* ---------------------------------
       Dynamic Headings
       --------------------------------- */
    public function headings(): array
    {
        return array_map(
            fn ($col) => strtoupper(str_replace('_', ' ', $col)),
            $this->columns
        );
    }

    public function title(): string
    {
        return 'NG Status Pivot By Process';
    }

    /* ---------------------------------
       Styling & Formatting
       --------------------------------- */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $columnCount = count($this->columns);
                if ($columnCount === 0) {
                    return;
                }

                $lastColumn = Coordinate::stringFromColumnIndex($columnCount);

                /* ---------- Title ---------- */
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', $this->reportTitle);

                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- Header ---------- */
                $headerRow = 3;
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
                    ->getFont()->setBold(true);

                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                /* ---------- Auto-fit ---------- */
                foreach (range(1, $columnCount) as $i) {
                    $sheet->getColumnDimension(
                        Coordinate::stringFromColumnIndex($i)
                    )->setAutoSize(true);
                }

                /* ---------- Borders ---------- */
                $startRow = $headerRow;
                $endRow   = $sheet->getHighestRow();

                $sheet->getStyle("A{$startRow}:{$lastColumn}{$endRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
               /** 🔹 Freeze header */
                $sheet->freezePane('A4');                    
            },        

         ];
    }
}