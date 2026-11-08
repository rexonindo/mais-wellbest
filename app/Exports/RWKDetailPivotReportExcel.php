<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;


class RWKDetailPivotReportExcel implements
    FromCollection,
    WithHeadings,
    WithTitle,
    WithEvents,
    WithCharts
{
    protected Collection $data;
    protected string $reportTitle;
    protected string $reportGroup;    
    protected array $columns = [];

    public function __construct(
        Collection $data,
        string $reportTitle = 'REWORK DETAIL BY GROUP',
        string $reportGroup = 'CATEGORY'
    ) {
        $this->data = $data;
        $this->reportTitle = $reportTitle;
        $this->reportGroup = $reportGroup;
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
        return $this->data->map(function ($item) {

            $row = $item->getAttributes();

            foreach ($row as $key => $value) {

                if (is_numeric($value)) {

                    $value = round((float)$value, 0);

                    // hide zero
                    if ((float)$value == 0) {
                        $row[$key] = null;
                    } else {
                        $row[$key] = $value;
                    }
                }
            }

            return $row;
        });
    }

    /* ---------------------------------
       HEADINGS
    --------------------------------- */
    public function headings(): array
    {
        $topHeader = [];
        $subHeader = [];

        foreach ($this->columns as $col) {

            $label = strtoupper(str_replace('_', ' ', $col));

            // fixed columns
            if (in_array($col, [
                'WO NO',
                'PART NO',
                'TYPE'
            ])) {

                $topHeader[] = $label;
                $subHeader[] = '';
                continue;
            }

            /*
               Dynamic format:
               PROCNAME_RWKNAME
            */
            if (preg_match('/^(.*?)_(.*)$/', $col, $matches)) {

                $process = strtoupper(
                    str_replace('_', ' ', $matches[1])
                );

                $rwkName = strtoupper(
                    str_replace('_', ' ', $matches[2])
                );

                $topHeader[] = $process;
                $subHeader[] = $rwkName;

            } else {

                $topHeader[] = $label;
                $subHeader[] = '';
            }
        }

        return [
            $topHeader,
            $subHeader
        ];
    }

    public function title(): string
    {
        return 'REWORK DETAIL REPORT';
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

                $sheet->getStyle('A1')->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- HEADER ---------- */

                $headerRow1 = 3;
                $headerRow2 = 4;

                $sheet->getStyle(
                    "A{$headerRow1}:{$lastColumn}{$headerRow2}"
                )->getFont()->setBold(true);

                $sheet->getStyle(
                    "A{$headerRow1}:{$lastColumn}{$headerRow2}"
                )->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- MERGE HEADER ---------- */

                $columns = $this->columns;
                $colIndex = 1;

                while ($colIndex <= count($columns)) {

                    $currentCol = $columns[$colIndex - 1];

                    // fixed columns
                    if (in_array($currentCol, [
                        'WO NO',
                        'PART NO',
                        'TYPE',
                        'OUT QTY',
                        'FGS QTY'
                    ])) {

                        $colLetter = Coordinate::stringFromColumnIndex($colIndex);

                        $sheet->mergeCells(
                            "{$colLetter}{$headerRow1}:{$colLetter}{$headerRow2}"
                        );

                        $colIndex++;
                        continue;
                    }

                    // extract process
                    if (preg_match('/^(.*?)_(.*)$/', $currentCol, $match)) {
                        $process = $match[1];
                    } else {
                        $process = $currentCol;
                    }

                    $start = $colIndex;

                    // group same process
                    while ($colIndex <= count($columns)) {

                        $nextCol = $columns[$colIndex - 1];

                        if (preg_match('/^(.*?)_(.*)$/', $nextCol, $m)) {
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

                    // merge process header
                    if ($end > $start) {

                        $startLetter = Coordinate::stringFromColumnIndex($start);
                        $endLetter   = Coordinate::stringFromColumnIndex($end);

                        $sheet->mergeCells(
                            "{$startLetter}{$headerRow1}:{$endLetter}{$headerRow1}"
                        );
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

                // dynamic numeric columns start after fixed cols
                foreach (range(5, $columnCount) as $i) {

                    $col = Coordinate::stringFromColumnIndex($i);

                    $sheet->setCellValue(
                        "{$col}{$totalRow}",
                        "=SUM({$col}{$dataStartRow}:{$col}{$dataEndRow})"
                    );
                }

                $sheet->getStyle(
                    "A{$totalRow}:{$lastColumn}{$totalRow}"
                )->getFont()->setBold(true);

                /* ---------- HIDE ZERO ---------- */

                foreach (range(5, $columnCount) as $i) {

                    $colLetter = Coordinate::stringFromColumnIndex($i);

                    $sheet->getStyle(
                        "{$colLetter}5:{$colLetter}{$totalRow}"
                    )
                    ->getNumberFormat()
                    ->setFormatCode('#,##0;-#,##0;;@');
                }

                /* ---------- BORDERS ---------- */

                $sheet->getStyle(
                    "A{$headerRow1}:{$lastColumn}{$totalRow}"
                )
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);


                /* ---------- TOTAL RWK BY GROUP SHEET ---------- */

                $spreadsheet = $sheet->getParent();

                /*
                |--------------------------------------------------------------------------
                | Create Chart Sheet
                |--------------------------------------------------------------------------
                */

                $chartSheet = $spreadsheet->createSheet();
                $chartSheet->setTitle('TOTAL RWK BY GROUP');

                /*
                |--------------------------------------------------------------------------
                | SHEET TITLE
                |--------------------------------------------------------------------------
                */

                $chartSheet->mergeCells('A1:D1');

                $chartSheet->setCellValue('A1', 'TOTAL RWK BY ' . $this->reportGroup);

                $chartSheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(16);

                $chartSheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /*
                |--------------------------------------------------------------------------
                | Build Chart Data
                |--------------------------------------------------------------------------
                */

                $chartSheet->setCellValue('A3', $this->reportGroup);
                $chartSheet->setCellValue('B3', 'RWK NAME');
                $chartSheet->setCellValue('C3', 'RWK QTY');
                $chartSheet->setCellValue('D3', 'RWK RATIO');
                $chartSheet->getStyle('A3:D3')->getFont()->setBold(true);     

                $chartRow = 4;

                /*
                |--------------------------------------------------------------------------
                | Get TOTAL OUT QTY (LINUX SAFE)
                |--------------------------------------------------------------------------
                */

                $inQtyColIndex = array_search('OUT QTY', $this->columns);

                $totalInQty = 0;

                if ($inQtyColIndex !== false) {

                    $inQtyColLetter = Coordinate::stringFromColumnIndex(
                        $inQtyColIndex + 1
                    );

                    for ($r = $dataStartRow; $r <= $dataEndRow; $r++) {

                        $cellValue = $sheet
                            ->getCell("{$inQtyColLetter}{$r}")
                            ->getValue();

                        $totalInQty += (float) $cellValue;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | RWK totals grouped by RWK NAME only (ignores process)
                | Used by the "TOTAL RWK BY NAME" sheet below
                |--------------------------------------------------------------------------
                */
                $rwkNameTotals = [];

                /*
                |--------------------------------------------------------------------------
                | Sum TOTAL per dynamic column
                |--------------------------------------------------------------------------
                */

                $groupTotals = [];
                $chartRows = [];

                foreach (range(5, $columnCount) as $i) {

                    $colName = $this->columns[$i - 1] ?? '';

                    /*
                    |--------------------------------------------------------------------------
                    | SKIP OUT QTY COLUMN
                    |--------------------------------------------------------------------------
                    */
                    if (strtoupper(trim($colName)) == 'OUT QTY' || strtoupper(trim($colName)) == 'FGS QTY') {
                        continue;
                    }

                    $colLetter = Coordinate::stringFromColumnIndex($i);

                    /*
                    Convert:
                    PROCNAME_RWKNAME
                    into RWKNAME only
                    */
                    if (preg_match('/^(.*?)_(.*)$/', $colName, $match)) {

                        $divName = strtoupper(
                            str_replace('_', ' ', $match[1])
                        );

                        $rwkName = strtoupper(
                            str_replace('_', ' ', $match[2])
                        );

                    } else {

                        $divName = '';
                        $rwkName = $colName;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | TOTAL RWK QTY (LINUX SAFE)
                    |--------------------------------------------------------------------------
                    */

                    $rwkQty = 0;

                    for ($r = $dataStartRow; $r <= $dataEndRow; $r++) {

                        $cellValue = $sheet
                            ->getCell("{$colLetter}{$r}")
                            ->getValue();

                        $rwkQty += (float) $cellValue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Accumulate by RWK NAME (for new sheet)
                    |--------------------------------------------------------------------------
                    */

                    if ($rwkQty != 0) {

                        $rwkNameTotals[$rwkName] =
                            ($rwkNameTotals[$rwkName] ?? 0) + $rwkQty;

                        $groupKey = $divName !== '' ? $divName : 'OTHER';
                        $groupTotals[$groupKey] = ($groupTotals[$groupKey] ?? 0) + $rwkQty;
                    }                    

                    /*
                    |--------------------------------------------------------------------------
                    | Percentage
                    |--------------------------------------------------------------------------
                    */

                    if ($totalInQty > 0) {

                        $totalValue = $rwkQty / $totalInQty;

                    } else {

                        $totalValue = 0;
                    }

                    /*
                    Skip empty totals
                    */
                    if ((float)$totalValue == 0) {
                        continue;
                    }

                    $chartRows[] = [
                        'div'   => $divName,
                        'rwk'    => $rwkName,
                        'qty'   => $rwkQty,
                        'ratio' => $totalValue,
                    ];

                }

                // Sort by category A-Z (blank category last); within a category the original column order is kept
                usort($chartRows, function ($a, $b) {
                    if ($a['div'] === '' && $b['div'] !== '') return 1;
                    if ($b['div'] === '' && $a['div'] !== '') return -1;
                    return strnatcasecmp($a['div'], $b['div']);
                });

                $prevDiv     = null;
                $divStartRow = $chartRow;

                foreach ($chartRows as $r) {

                    // Category changed: close the previous group and merge it
                    if ($r['div'] !== $prevDiv) {

                        if ($prevDiv !== null && $prevDiv !== '' && ($chartRow - 1) > $divStartRow) {
                            $chartSheet->mergeCells("A{$divStartRow}:A" . ($chartRow - 1));
                        }

                        $divStartRow = $chartRow;
                        $prevDiv     = $r['div'];

                        $chartSheet->setCellValue("A{$chartRow}", $r['div']);
                    }

                    $chartSheet->setCellValue("B{$chartRow}", $r['rwk']);
                    $chartSheet->setCellValue("C{$chartRow}", $r['qty']);
                    $chartSheet->setCellValue("D{$chartRow}", $r['ratio']);

                    $chartSheet->getStyle("C{$chartRow}")
                        ->getNumberFormat()->setFormatCode('#,##0');
                    $chartSheet->getStyle("D{$chartRow}")
                        ->getNumberFormat()->setFormatCode('0.00%');

                    $chartRow++;
                }

                // Merge the last group
                if ($prevDiv !== null && $prevDiv !== '' && ($chartRow - 1) > $divStartRow) {
                    $chartSheet->mergeCells("A{$divStartRow}:A" . ($chartRow - 1));
                }
                /*
                |--------------------------------------------------------------------------
                | Auto Size
                |--------------------------------------------------------------------------
                */

                $chartSheet->getColumnDimension('A')->setAutoSize(true);
                $chartSheet->getColumnDimension('B')->setAutoSize(true);
                $chartSheet->getColumnDimension('C')->setAutoSize(true);
                $chartSheet->getColumnDimension('D')->setAutoSize(true);

                if ($chartRow > 4) {
                    $chartSheet->getStyle("A4:D" . ($chartRow - 1))
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                    $chartSheet->getStyle("A3:D" . ($chartRow - 1))
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                }              

                /*
                |--------------------------------------------------------------------------
                | Create Chart
                |--------------------------------------------------------------------------
                */

                $dataSeriesLabels = [
                    new DataSeriesValues(
                        DataSeriesValues::DATASERIES_TYPE_STRING,
                        "'TOTAL RWK BY GROUP'!\$D\$3",
                        null,
                        1
                    )
                ];

                $xAxisTickValues = [
                    new DataSeriesValues(
                        DataSeriesValues::DATASERIES_TYPE_STRING,
                        "'TOTAL RWK BY GROUP'!\$B\$4:\$B\$" . ($chartRow - 1),
                        null,
                        ($chartRow - 2)
                    )
                ];

                $dataSeriesValues = [
                    new DataSeriesValues(
                        DataSeriesValues::DATASERIES_TYPE_NUMBER,
                        "'TOTAL RWK BY GROUP'!\$D\$4:\$D\$" . ($chartRow - 1),
                        '0.00%',
                        ($chartRow - 2)
                    )
                ];

                /*
                |--------------------------------------------------------------------------
                | Plot
                |--------------------------------------------------------------------------
                */

                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART,
                    DataSeries::GROUPING_CLUSTERED,
                    range(0, count($dataSeriesValues) - 1),
                    $dataSeriesLabels,
                    $xAxisTickValues,
                    $dataSeriesValues
                );

                $series->setPlotDirection(
                    DataSeries::DIRECTION_COL
                );

                $plotArea = new PlotArea(null, [$series]);

                $legend = new Legend(
                    Legend::POSITION_RIGHT,
                    null,
                    false
                );

                $title = new Title('RWK RATIO CHART');

                $chart = new Chart(
                    'rwk_chart',
                    $title,
                    $legend,
                    $plotArea
                );

                /*
                |--------------------------------------------------------------------------
                | Chart Position
                |--------------------------------------------------------------------------
                */

                $chart->setTopLeftPosition('J2');
                $chart->setBottomRightPosition('S20');

                /*
                |--------------------------------------------------------------------------
                | Add Chart
                |--------------------------------------------------------------------------
                */

                $chartSheet->addChart($chart);

                /* ---------- RWK RATIO BY GROUP (summary table + chart) ---------- */

                arsort($groupTotals);

                $chartSheet->setCellValue('F3', $this->reportGroup);
                $chartSheet->setCellValue('G3', 'RWK QTY');
                $chartSheet->setCellValue('H3', 'RWK RATIO');

                $chartSheet->getStyle('F3:H3')->getFont()->setBold(true);
                $chartSheet->getStyle('F3:H3')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $grpRow = 4;

                foreach ($groupTotals as $grpName => $grpQty) {

                    $chartSheet->setCellValue("F{$grpRow}", $grpName);
                    $chartSheet->setCellValue("G{$grpRow}", $grpQty);
                    $chartSheet->setCellValue(
                        "H{$grpRow}",
                        $totalInQty > 0 ? $grpQty / $totalInQty : 0
                    );

                    $chartSheet->getStyle("G{$grpRow}")
                        ->getNumberFormat()->setFormatCode('#,##0');
                    $chartSheet->getStyle("H{$grpRow}")
                        ->getNumberFormat()->setFormatCode('0.00%');

                    $grpRow++;
                }

                $grpLast = $grpRow - 1;

                $chartSheet->getColumnDimension('F')->setAutoSize(true);
                $chartSheet->getColumnDimension('G')->setAutoSize(true);
                $chartSheet->getColumnDimension('H')->setAutoSize(true);

                if ($grpLast >= 4) {

                    $chartSheet->getStyle("F3:H{$grpLast}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);

                    $grpCount = $grpLast - 3;

                    $grpLabels = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'TOTAL RWK BY GROUP'!\$H\$3",
                            null,
                            1
                        )
                    ];

                    $grpCategories = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'TOTAL RWK BY GROUP'!\$F\$4:\$F\${$grpLast}",
                            null,
                            $grpCount
                        )
                    ];

                    $grpValues = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_NUMBER,
                            "'TOTAL RWK BY GROUP'!\$H\$4:\$H\${$grpLast}",
                            '0.00%',
                            $grpCount
                        )
                    ];

                    $grpSeries = new DataSeries(
                        DataSeries::TYPE_BARCHART,
                        DataSeries::GROUPING_CLUSTERED,
                        range(0, count($grpValues) - 1),
                        $grpLabels,
                        $grpCategories,
                        $grpValues
                    );

                    $grpSeries->setPlotDirection(DataSeries::DIRECTION_COL);

                    $grpChart = new Chart(
                        'rwk_ratio_by_group_chart',
                        new Title('RWK RATIO BY ' . $this->reportGroup),
                        new Legend(Legend::POSITION_RIGHT, null, false),
                        new PlotArea(null, [$grpSeries])
                    );

                    $grpChart->setTopLeftPosition('J22');
                    $grpChart->setBottomRightPosition('S40');

                    $chartSheet->addChart($grpChart);
                }                

                /*
                |==========================================================================
                | NEW SHEET : TOTAL RWK BY NAME
                | Summary of RWK QTY by RWK NAME only (process is NOT considered)
                |==========================================================================
                */

                $rwkSheetTitle = 'TOTAL RWK BY NAME';

                $rwkSheet = $spreadsheet->createSheet();
                $rwkSheet->setTitle($rwkSheetTitle);

                /* ---------- SHEET TITLE ---------- */

                $rwkSheet->mergeCells('A1:C1');

                $rwkSheet->setCellValue('A1', 'TOTAL RWK BY NAME');

                $rwkSheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(16);

                $rwkSheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- HEADER ---------- */

                $rwkSheet->setCellValue('A3', 'RWK NAME');
                $rwkSheet->setCellValue('B3', 'RWK QTY');
                $rwkSheet->setCellValue('C3', 'RWK RATIO');

                $rwkSheet->getStyle('A3:C3')->getFont()->setBold(true);

                $rwkSheet->getStyle('A3:C3')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- DATA (largest RWK QTY first) ---------- */

                arsort($rwkNameTotals);

                $rwkRow = 4;

                foreach ($rwkNameTotals as $rwkName => $rwkQty) {

                    $rwkSheet->setCellValue("A{$rwkRow}", $rwkName);
                    $rwkSheet->setCellValue("B{$rwkRow}", $rwkQty);
                    $rwkSheet->setCellValue(
                        "C{$rwkRow}",
                        $totalInQty > 0 ? $rwkQty / $totalInQty : 0
                    );

                    $rwkSheet->getStyle("B{$rwkRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $rwkSheet->getStyle("C{$rwkRow}")
                        ->getNumberFormat()
                        ->setFormatCode('0.00%');

                    $rwkRow++;
                }

                $rwkLastDataRow = $rwkRow - 1;

                /* ---------- TOTAL ROW + BORDERS ---------- */

                if ($rwkLastDataRow >= 4) {

                    $rwkSheet->setCellValue("A{$rwkRow}", 'TOTAL');
                    $rwkSheet->setCellValue("B{$rwkRow}", "=SUM(B4:B{$rwkLastDataRow})");
                    $rwkSheet->setCellValue("C{$rwkRow}", "=SUM(C4:C{$rwkLastDataRow})");

                    $rwkSheet->getStyle("B{$rwkRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $rwkSheet->getStyle("C{$rwkRow}")
                        ->getNumberFormat()
                        ->setFormatCode('0.00%');

                    $rwkSheet->getStyle("A{$rwkRow}:C{$rwkRow}")
                        ->getFont()->setBold(true);

                    $rwkSheet->getStyle("A3:C{$rwkRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                }

                /* ---------- AUTO SIZE ---------- */

                $rwkSheet->getColumnDimension('A')->setAutoSize(true);
                $rwkSheet->getColumnDimension('B')->setAutoSize(true);
                $rwkSheet->getColumnDimension('C')->setAutoSize(true);

                if ($rwkRow > 4) {
                    $rwkSheet->getStyle("A4:C" . ($rwkRow - 1))
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }                   

                /* ---------- CHART (only when there is data) ---------- */

                if ($rwkLastDataRow >= 4) {

                    $rwkPointCount = $rwkLastDataRow - 3;

                    $rwkLabels = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'{$rwkSheetTitle}'!\$B\$3",
                            null,
                            1
                        )
                    ];

                    $rwkCategories = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'{$rwkSheetTitle}'!\$A\$4:\$A\${$rwkLastDataRow}",
                            null,
                            $rwkPointCount
                        )
                    ];

                    $rwkValues = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_NUMBER,
                            "'{$rwkSheetTitle}'!\$B\$4:\$B\${$rwkLastDataRow}",
                            null,
                            $rwkPointCount
                        )
                    ];

                    $rwkSeries = new DataSeries(
                        DataSeries::TYPE_BARCHART,
                        DataSeries::GROUPING_CLUSTERED,
                        range(0, count($rwkValues) - 1),
                        $rwkLabels,
                        $rwkCategories,
                        $rwkValues
                    );

                    $rwkSeries->setPlotDirection(DataSeries::DIRECTION_COL);

                    $rwkChart = new Chart(
                        'rwk_total_by_name_chart',
                        new Title('TOTAL RWK QTY BY RWK NAME'),
                        new Legend(Legend::POSITION_RIGHT, null, false),
                        new PlotArea(null, [$rwkSeries])
                    );

                    $rwkChart->setTopLeftPosition('E2');
                    $rwkChart->setBottomRightPosition('O20');

                    $rwkSheet->addChart($rwkChart);

                    /* ---------- RWK RATIO CHART (below the QTY chart) ---------- */

                    $ratioLabels = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'{$rwkSheetTitle}'!\$C\$3",
                            null,
                            1
                        )
                    ];

                    $ratioCategories = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'{$rwkSheetTitle}'!\$A\$4:\$A\${$rwkLastDataRow}",
                            null,
                            $rwkPointCount
                        )
                    ];

                    $ratioValues = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_NUMBER,
                            "'{$rwkSheetTitle}'!\$C\$4:\$C\${$rwkLastDataRow}",
                            '0.00%',
                            $rwkPointCount
                        )
                    ];

                    $ratioSeries = new DataSeries(
                        DataSeries::TYPE_BARCHART,
                        DataSeries::GROUPING_CLUSTERED,
                        range(0, count($ratioValues) - 1),
                        $ratioLabels,
                        $ratioCategories,
                        $ratioValues
                    );

                    $ratioSeries->setPlotDirection(DataSeries::DIRECTION_COL);

                    $ratioChart = new Chart(
                        'rwk_total_by_name_ratio_chart',
                        new Title('RWK RATIO CHART'),
                        new Legend(Legend::POSITION_RIGHT, null, false),
                        new PlotArea(null, [$ratioSeries])
                    );

                    $ratioChart->setTopLeftPosition('E22');
                    $ratioChart->setBottomRightPosition('O40');

                    $rwkSheet->addChart($ratioChart);
                }


                /* ---------- FREEZE ---------- */

                $sheet->freezePane('A5');
            }
        ];
    }

    public function charts()
    {
        return [];
    }
}
