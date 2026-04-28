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

class WOProgressPivotReportExcel implements
    FromCollection,
    WithHeadings,
    WithTitle,
    WithEvents
{
    protected Collection $data;
    protected string $reportTitle;
    protected array $columns = [];

    public function __construct(Collection $data, string $reportTitle = 'WO Progress Pivot By Process')
    {
        $this->data = $data;
        $this->reportTitle = $reportTitle;

        if ($data->isNotEmpty()) {
            $this->columns = array_keys(
                $data->first()->getAttributes()
            );
        }
    }

    /* ---------------------------------
       DATA
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
       2-ROW HEADINGS
    --------------------------------- */
    public function headings(): array
    {
        $topHeader = [];
        $subHeader = [];

        foreach ($this->columns as $col) {

            $label = strtoupper(str_replace('_', ' ', $col));

            // Fixed columns → vertical merge
            if (in_array($col, ['WO NO', 'PART NO', 'TYPE', 'END DATE', 'WO QTY', 'CAV'])) {
                $topHeader[] = $label;
                $subHeader[] = '';
                continue;
            }

            // Dynamic columns: PROCNAME_IN_QTY
            preg_match('/^(.*)_(IN|OK|RWK|NG|TTL)_QTY$/', $col, $matches);

            if ($matches) {
                $process = strtoupper(str_replace('_', ' ', $matches[1]));
                $type    = $matches[2];

                $topHeader[] = $process;
                $subHeader[] = $type . ' QTY';
            } else {
                $topHeader[] = $label;
                $subHeader[] = '';
            }
        }

        return [
            $topHeader,
            $subHeader,
        ];
    }

    public function title(): string
    {
        return $this->reportTitle;
    }

    /* ---------------------------------
       STYLING
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

                /* ---------- TITLE ---------- */
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', $this->reportTitle);

                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- HEADER ---------- */
                $headerRow1 = 3;
                $headerRow2 = 4;

                // Bold + center
                $sheet->getStyle("A{$headerRow1}:{$lastColumn}{$headerRow2}")
                    ->getFont()->setBold(true);

                $sheet->getStyle("A{$headerRow1}:{$lastColumn}{$headerRow2}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- MERGE HEADER ---------- */
                $columns = $this->columns;
                $colIndex = 1;

                while ($colIndex <= count($columns)) {

                    $currentCol = $columns[$colIndex - 1];

                    // Fixed columns → vertical merge
                    if (in_array($currentCol, ['WO NO', 'PART NO', 'TYPE', 'END DATE', 'WO QTY', 'CAV'])) {

                        $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                        $sheet->mergeCells("{$colLetter}{$headerRow1}:{$colLetter}{$headerRow2}");

                        $colIndex++;
                        continue;
                    }

                    // ✅ Extract process name properly
                    if (preg_match('/^(.*)_(IN|OK|RWK|NG|TTL)_QTY$/', $currentCol, $match)) {
                        $process = $match[1];
                    } else {
                        $process = $currentCol;
                    }

                    $start = $colIndex;

                    //  group SAME PROCESS ONLY
                    while ($colIndex <= count($columns)) {

                        $nextCol = $columns[$colIndex - 1];

                        if (preg_match('/^(.*)_(IN|OK|RWK|NG|TTL)_QTY$/', $nextCol, $m)) {
                            $nextProcess = $m[1];
                        } else {
                            $nextProcess = $nextCol;
                        }

                        if ($nextProcess !== $process) {
                            break;
                        }

                        $colIndex++;
                    }

                    $end = $colIndex - 1;

                    //  Merge ONLY if more than 1 column
                    if ($end > $start) {
                        $startLetter = Coordinate::stringFromColumnIndex($start);
                        $endLetter   = Coordinate::stringFromColumnIndex($end);

                        $sheet->mergeCells("{$startLetter}{$headerRow1}:{$endLetter}{$headerRow1}");
                    }
                }

                /* ---------- AUTO SIZE ---------- */
                foreach (range(1, $columnCount) as $i) {
                    $sheet->getColumnDimension(
                        Coordinate::stringFromColumnIndex($i)
                    )->setAutoSize(true);
                }

                /* ---------- TOTAL ROW ---------- */
                $dataStartRow = 5;
                $dataEndRow   = $sheet->getHighestRow();
                $totalRow     = $dataEndRow + 1;

                $sheet->setCellValue("A{$totalRow}", "TOTAL");

                foreach (range(7, $columnCount) as $i) {
                    $col = Coordinate::stringFromColumnIndex($i);

                    $sheet->setCellValue(
                        "{$col}{$totalRow}",
                        "=SUM({$col}{$dataStartRow}:{$col}{$dataEndRow})"
                    );
                }

                $sheet->getStyle("A{$totalRow}:{$lastColumn}{$totalRow}")
                    ->getFont()->setBold(true);

                /* ---------- BORDERS ---------- */
                $sheet->getStyle("A{$headerRow1}:{$lastColumn}{$totalRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /* ---------- FREEZE ---------- */
                $sheet->freezePane('A5');
            }
        ];
    }
}