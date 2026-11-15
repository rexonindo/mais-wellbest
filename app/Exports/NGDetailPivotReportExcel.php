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
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use Carbon\Carbon;

class NGDetailPivotReportExcel implements
    FromCollection,
    WithHeadings,
    WithTitle,
    WithEvents,
    WithCharts
{
    protected Collection $data;
    protected string $reportTitle;
    protected string $reportGroup;    
    protected ?string $dateFrom = null;
    protected ?string $dateTo = null;
    protected array $columns = [];

    protected float $ngStandard = 0.009; // 0.90%

    public function __construct(
        Collection $data,
        string $reportTitle = 'NG DETAIL BY GROUP',
        string $reportGroup = 'CATEGORY',
        ?string $dateFrom = null,
        ?string $dateTo = null
    ) {
        $this->data = $data;
        $this->reportTitle = $reportTitle;
        $this->reportGroup = $reportGroup;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        if ($data->isNotEmpty()) {
            $this->columns = array_keys(
                $data->first()->getAttributes()
            );
        }
    }

    protected function getPrintDateText(): string
    {
        $fmt = fn ($d) => Carbon::parse($d)->format('d-M-Y');

        if ($this->dateFrom && $this->dateTo) {
            return 'Print Date : ' . $fmt($this->dateFrom) . ' until ' . $fmt($this->dateTo);
        }
        if ($this->dateFrom) {
            return 'Print Date : ' . $fmt($this->dateFrom) . ' until -';
        }
        if ($this->dateTo) {
            return 'Print Date : - until ' . $fmt($this->dateTo);
        }

        return 'Print Date : All dates';
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
               PROCNAME_NGNAME
            */
            if (preg_match('/^(.*?)_(.*)$/', $col, $matches)) {

                $process = strtoupper(
                    str_replace('_', ' ', $matches[1])
                );

                $ngName = strtoupper(
                    str_replace('_', ' ', $matches[2])
                );

                $topHeader[] = $process;
                $subHeader[] = $ngName;

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
        return 'NG DETAIL REPORT';
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

                // Two lines in A1: bold title + smaller date range
                $rich = new RichText();

                $titleRun = $rich->createTextRun($this->reportTitle);
                $titleRun->getFont()->setBold(true)->setSize(14);

                $dateRun = $rich->createTextRun("\n" . $this->getPrintDateText());
                $dateRun->getFont()->setBold(false)->setSize(11);

                $sheet->getCell('A1')->setValue($rich);

                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getRowDimension(1)->setRowHeight(40);

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


                /* ---------- TOTAL NG BY GROUP SHEET ---------- */

                $spreadsheet = $sheet->getParent();

                /*
                |--------------------------------------------------------------------------
                | Create Chart Sheet
                |--------------------------------------------------------------------------
                */

                $chartSheet = $spreadsheet->createSheet();
                $chartSheet->setTitle('TOTAL NG BY GROUP');

                /*
                |--------------------------------------------------------------------------
                | SHEET TITLE
                |--------------------------------------------------------------------------
                */

                $chartSheet->mergeCells('A1:D1');

                $chartSheet->setCellValue('A1', 'TOTAL NG BY ' . $this->reportGroup);

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
                $chartSheet->setCellValue('B3', 'NG NAME');
                $chartSheet->setCellValue('C3', 'NG QTY');
                $chartSheet->setCellValue('D3', 'NG RATIO');
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
                | NG totals grouped by NG NAME only (ignores process)
                | Used by the "TOTAL NG BY NAME" sheet below
                |--------------------------------------------------------------------------
                */
                $ngNameTotals = [];

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
                    PROCNAME_NGNAME
                    into NGNAME only
                    */
                    if (preg_match('/^(.*?)_(.*)$/', $colName, $match)) {

                        $divName = strtoupper(
                            str_replace('_', ' ', $match[1])
                        );

                        $ngName = strtoupper(
                            str_replace('_', ' ', $match[2])
                        );

                    } else {

                        $divName = '';
                        $ngName = $colName;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | TOTAL NG QTY (LINUX SAFE)
                    |--------------------------------------------------------------------------
                    */

                    $ngQty = 0;

                    for ($r = $dataStartRow; $r <= $dataEndRow; $r++) {

                        $cellValue = $sheet
                            ->getCell("{$colLetter}{$r}")
                            ->getValue();

                        $ngQty += (float) $cellValue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Accumulate by NG NAME (for new sheet)
                    |--------------------------------------------------------------------------
                    */

                    if ($ngQty != 0) {

                        $ngNameTotals[$ngName] =
                            ($ngNameTotals[$ngName] ?? 0) + $ngQty;

                        $groupKey = $divName !== '' ? $divName : 'OTHER';
                        $groupTotals[$groupKey] = ($groupTotals[$groupKey] ?? 0) + $ngQty;
                    }                    

                    /*
                    |--------------------------------------------------------------------------
                    | Percentage
                    |--------------------------------------------------------------------------
                    */

                    if ($totalInQty > 0) {

                        $totalValue = $ngQty / $totalInQty;

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
                        'ng'    => $ngName,
                        'qty'   => $ngQty,
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

                    $chartSheet->setCellValue("B{$chartRow}", $r['ng']);
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

                /* ---------- TOTAL ROW + STANDARD COLUMN ---------- */

                $grandNgQty   = array_sum($groupTotals);
                $grandNgRatio = $totalInQty > 0 ? $grandNgQty / $totalInQty : 0;

                if ($chartRow > 4) {

                    $chartSheet->setCellValue("A{$chartRow}", 'TOTAL');
                    $chartSheet->setCellValue("C{$chartRow}", $grandNgQty);
                    $chartSheet->setCellValue("D{$chartRow}", $grandNgRatio);

                    $chartSheet->getStyle("C{$chartRow}")->getNumberFormat()->setFormatCode('#,##0');
                    $chartSheet->getStyle("D{$chartRow}")->getNumberFormat()->setFormatCode('0.00%');
                    $chartSheet->getStyle("A{$chartRow}:E{$chartRow}")->getFont()->setBold(true);

                    $chartRow++;
                }

                $chartSheet->setCellValue('E3', 'STANDARD (0.90%)');
                $chartSheet->getStyle('E3')->getFont()->setBold(true);

                for ($r = 4; $r < $chartRow; $r++) {
                    $chartSheet->setCellValue("E{$r}", $this->ngStandard);
                    $chartSheet->getStyle("E{$r}")->getNumberFormat()->setFormatCode('0.00%');
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
                $chartSheet->getColumnDimension('E')->setAutoSize(true);

                if ($chartRow > 4) {
                    $chartSheet->getStyle("A4:E" . ($chartRow - 1))
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                    $chartSheet->getStyle("A3:E" . ($chartRow - 1))
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
                        "'TOTAL NG BY GROUP'!\$D\$3",
                        null,
                        1
                    )
                ];

                $lastRow = $chartRow - 1;
                $count   = $chartRow - 4;

                $xAxisTickValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,
                        "'TOTAL NG BY GROUP'!\$B\$4:\$B\${$lastRow}", null, $count)
                ];

                /* Bar series: NG RATIO */
                $barSeries = new DataSeries(
                    DataSeries::TYPE_BARCHART,
                    DataSeries::GROUPING_CLUSTERED,
                    [0],
                    [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'TOTAL NG BY GROUP'!\$D\$3", null, 1)],
                    $xAxisTickValues,
                    [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,
                        "'TOTAL NG BY GROUP'!\$D\$4:\$D\${$lastRow}", '0.00%', $count)]
                );
                $barSeries->setPlotDirection(DataSeries::DIRECTION_COL);

                /* Line series: STANDARD 0.90% */
                $stdValues = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,
                    "'TOTAL NG BY GROUP'!\$E\$4:\$E\${$lastRow}", '0.00%', $count);
                $stdValues->setPointMarker('square');

                $lineSeries = new DataSeries(
                    DataSeries::TYPE_LINECHART,
                    DataSeries::GROUPING_STANDARD,
                    [1],
                    [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'TOTAL NG BY GROUP'!\$E\$3", null, 1)],
                    $xAxisTickValues,
                    [$stdValues]
                );
                $lineSeries->setPlotDirection(DataSeries::DIRECTION_COL);
                $lineSeries->setSmoothLine(false);

                $plotArea = new PlotArea(null, [$barSeries, $lineSeries]);

                $legend = new Legend(Legend::POSITION_BOTTOM, null, false);
   
                $title = new Title('NG RATIO CHART');

                $chart = new Chart(
                    'ng_chart',
                    $title,
                    $legend,
                    $plotArea
                );

                /*
                |--------------------------------------------------------------------------
                | Chart Position
                |--------------------------------------------------------------------------
                */

                $chart->setTopLeftPosition('L2');
                $chart->setBottomRightPosition('U20');

                /*
                |--------------------------------------------------------------------------
                | Add Chart
                |--------------------------------------------------------------------------
                */

                $chartSheet->addChart($chart);

                /* ---------- NG RATIO BY GROUP (summary table + chart) ---------- */

                arsort($groupTotals);

                $chartSheet->setCellValue('G3', $this->reportGroup);
                $chartSheet->setCellValue('H3', 'NG QTY');
                $chartSheet->setCellValue('I3', 'NG RATIO');

                $chartSheet->getStyle('G3:I3')->getFont()->setBold(true);
                $chartSheet->getStyle('G3:I3')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $grpRow = 4;

                foreach ($groupTotals as $grpName => $grpQty) {

                    $chartSheet->setCellValue("G{$grpRow}", $grpName);
                    $chartSheet->setCellValue("H{$grpRow}", $grpQty);
                    $chartSheet->setCellValue(
                        "I{$grpRow}",
                        $totalInQty > 0 ? $grpQty / $totalInQty : 0
                    );

                    $chartSheet->getStyle("H{$grpRow}")
                        ->getNumberFormat()->setFormatCode('#,##0');
                    $chartSheet->getStyle("I{$grpRow}")
                        ->getNumberFormat()->setFormatCode('0.00%');

                    $grpRow++;
                }

                /* TOTAL row */
                $chartSheet->setCellValue("G{$grpRow}", 'TOTAL');
                $chartSheet->setCellValue("H{$grpRow}", array_sum($groupTotals));
                $chartSheet->setCellValue("I{$grpRow}", $totalInQty > 0 ? array_sum($groupTotals) / $totalInQty : 0);
                $chartSheet->getStyle("H{$grpRow}")->getNumberFormat()->setFormatCode('#,##0');
                $chartSheet->getStyle("I{$grpRow}")->getNumberFormat()->setFormatCode('0.00%');
                $chartSheet->getStyle("G{$grpRow}:J{$grpRow}")->getFont()->setBold(true);
                $grpRow++;

                /* STANDARD column */
                $chartSheet->setCellValue('J3', 'STANDARD (0.90%)');
                $chartSheet->getStyle('J3')->getFont()->setBold(true);
                for ($r = 4; $r < $grpRow; $r++) {
                    $chartSheet->setCellValue("J{$r}", $this->ngStandard);
                    $chartSheet->getStyle("J{$r}")->getNumberFormat()->setFormatCode('0.00%');
                }

                $grpLast = $grpRow - 1;

                $chartSheet->getColumnDimension('G')->setAutoSize(true);
                $chartSheet->getColumnDimension('H')->setAutoSize(true);
                $chartSheet->getColumnDimension('I')->setAutoSize(true);
                $chartSheet->getColumnDimension('J')->setAutoSize(true);

                if ($grpLast >= 4) {

                    $chartSheet->getStyle("G3:J{$grpLast}")
                        ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                    $grpCount = $grpLast - 3;

                    $grpCategories = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'TOTAL NG BY GROUP'!\$G\$4:\$G\${$grpLast}", null, $grpCount)
                    ];

                    $grpBar = new DataSeries(
                        DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, [0],
                        [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'TOTAL NG BY GROUP'!\$I\$3", null, 1)],
                        $grpCategories,
                        [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,
                            "'TOTAL NG BY GROUP'!\$I\$4:\$I\${$grpLast}", '0.00%', $grpCount)]
                    );
                    $grpBar->setPlotDirection(DataSeries::DIRECTION_COL);

                    $grpStdValues = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,
                        "'TOTAL NG BY GROUP'!\$J\$4:\$J\${$grpLast}", '0.00%', $grpCount);
                    $grpStdValues->setPointMarker('square');

                    $grpLine = new DataSeries(
                        DataSeries::TYPE_LINECHART, DataSeries::GROUPING_STANDARD, [1],
                        [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'TOTAL NG BY GROUP'!\$J\$3", null, 1)],
                        $grpCategories,
                        [$grpStdValues]
                    );
                    $grpLine->setPlotDirection(DataSeries::DIRECTION_COL);
                    $grpLine->setSmoothLine(false);

                    $grpChart = new Chart(
                        'ng_ratio_by_group_chart',
                        new Title('NG RATIO BY ' . $this->reportGroup),
                        new Legend(Legend::POSITION_BOTTOM, null, false),
                        new PlotArea(null, [$grpBar, $grpLine])
                    );

                    $grpChart->setTopLeftPosition('L22');
                    $grpChart->setBottomRightPosition('U40');

                    $chartSheet->addChart($grpChart);
                }

                /*
                |==========================================================================
                | NEW SHEET : TOTAL NG BY NAME
                | Summary of NG QTY by NG NAME only (process is NOT considered)
                |==========================================================================
                */

                $ngSheetTitle = 'TOTAL NG BY NAME';

                $ngSheet = $spreadsheet->createSheet();
                $ngSheet->setTitle($ngSheetTitle);

                /* ---------- SHEET TITLE ---------- */

                $ngSheet->mergeCells('A1:C1');

                $ngSheet->setCellValue('A1', 'TOTAL NG BY NAME');

                $ngSheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(16);

                $ngSheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- HEADER ---------- */

                $ngSheet->setCellValue('A3', 'NG NAME');
                $ngSheet->setCellValue('B3', 'NG QTY');
                $ngSheet->setCellValue('C3', 'NG RATIO');

                $ngSheet->getStyle('A3:C3')->getFont()->setBold(true);

                $ngSheet->getStyle('A3:C3')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* ---------- DATA (largest NG QTY first) ---------- */

                arsort($ngNameTotals);

                $ngRow = 4;

                foreach ($ngNameTotals as $ngName => $ngQty) {

                    $ngSheet->setCellValue("A{$ngRow}", $ngName);
                    $ngSheet->setCellValue("B{$ngRow}", $ngQty);
                    $ngSheet->setCellValue(
                        "C{$ngRow}",
                        $totalInQty > 0 ? $ngQty / $totalInQty : 0
                    );

                    $ngSheet->getStyle("B{$ngRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $ngSheet->getStyle("C{$ngRow}")
                        ->getNumberFormat()
                        ->setFormatCode('0.00%');

                    $ngRow++;
                }

                $ngLastDataRow = $ngRow - 1;

                /* ---------- TOTAL ROW + BORDERS ---------- */

                if ($ngLastDataRow >= 4) {

                    $ngSheet->setCellValue("A{$ngRow}", 'TOTAL');
                    $ngSheet->setCellValue("B{$ngRow}", "=SUM(B4:B{$ngLastDataRow})");
                    $ngSheet->setCellValue("C{$ngRow}", "=SUM(C4:C{$ngLastDataRow})");

                    $ngSheet->getStyle("B{$ngRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $ngSheet->getStyle("C{$ngRow}")
                        ->getNumberFormat()
                        ->setFormatCode('0.00%');

                    $ngSheet->getStyle("A{$ngRow}:C{$ngRow}")
                        ->getFont()->setBold(true);

                    $ngSheet->getStyle("A3:C{$ngRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                }

                /* ---------- AUTO SIZE ---------- */

                $ngSheet->getColumnDimension('A')->setAutoSize(true);
                $ngSheet->getColumnDimension('B')->setAutoSize(true);
                $ngSheet->getColumnDimension('C')->setAutoSize(true);

                if ($ngRow > 4) {
                    $ngSheet->getStyle("A4:C" . ($ngRow - 1))
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }                   

                /* ---------- CHART (only when there is data) ---------- */

                if ($ngLastDataRow >= 4) {

                    $ngPointCount = $ngLastDataRow - 3;

                    $ngLabels = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'{$ngSheetTitle}'!\$B\$3",
                            null,
                            1
                        )
                    ];

                    $ngCategories = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'{$ngSheetTitle}'!\$A\$4:\$A\${$ngLastDataRow}",
                            null,
                            $ngPointCount
                        )
                    ];

                    $ngValues = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_NUMBER,
                            "'{$ngSheetTitle}'!\$B\$4:\$B\${$ngLastDataRow}",
                            null,
                            $ngPointCount
                        )
                    ];

                    $ngSeries = new DataSeries(
                        DataSeries::TYPE_BARCHART,
                        DataSeries::GROUPING_CLUSTERED,
                        range(0, count($ngValues) - 1),
                        $ngLabels,
                        $ngCategories,
                        $ngValues
                    );

                    $ngSeries->setPlotDirection(DataSeries::DIRECTION_COL);

                    $ngChart = new Chart(
                        'ng_total_by_name_chart',
                        new Title('TOTAL NG QTY BY NG NAME'),
                        new Legend(Legend::POSITION_RIGHT, null, false),
                        new PlotArea(null, [$ngSeries])
                    );

                    $ngChart->setTopLeftPosition('E2');
                    $ngChart->setBottomRightPosition('O20');

                    $ngSheet->addChart($ngChart);

                    /* ---------- NG RATIO CHART (below the QTY chart) ---------- */

                    $ratioLabels = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'{$ngSheetTitle}'!\$C\$3",
                            null,
                            1
                        )
                    ];

                    $ratioCategories = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "'{$ngSheetTitle}'!\$A\$4:\$A\${$ngLastDataRow}",
                            null,
                            $ngPointCount
                        )
                    ];

                    $ratioValues = [
                        new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_NUMBER,
                            "'{$ngSheetTitle}'!\$C\$4:\$C\${$ngLastDataRow}",
                            '0.00%',
                            $ngPointCount
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
                        'ng_total_by_name_ratio_chart',
                        new Title('NG RATIO CHART'),
                        new Legend(Legend::POSITION_RIGHT, null, false),
                        new PlotArea(null, [$ratioSeries])
                    );

                    $ratioChart->setTopLeftPosition('E22');
                    $ratioChart->setBottomRightPosition('O40');

                    $ngSheet->addChart($ratioChart);
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
