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
    protected array $columns = [];

    public function __construct(
        Collection $data,
        string $reportTitle = 'REWORK DETAIL PIVOT BY PROCESS'
    ) {
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
                'TYPE',
                'REMARKS'
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
                        'REMARKS',
                        'IN QTY'
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


                /* ---------- CHART SHEET ---------- */

                $spreadsheet = $sheet->getParent();

                /*
                |--------------------------------------------------------------------------
                | Create Chart Sheet
                |--------------------------------------------------------------------------
                */

                $chartSheet = $spreadsheet->createSheet();
                $chartSheet->setTitle('RWK Chart');

                /*
                |--------------------------------------------------------------------------
                | SHEET TITLE
                |--------------------------------------------------------------------------
                */

                $chartSheet->mergeCells('A1:B1');

                $chartSheet->setCellValue('A1', 'REWORK RATIO');

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

                $chartSheet->setCellValue('A3', 'RWK INFO');
                $chartSheet->setCellValue('B3', 'TOTAL QTY');

                $chartRow = 4;

                /*
                |--------------------------------------------------------------------------
                | Get TOTAL IN QTY
                |--------------------------------------------------------------------------
                */

                $inQtyColIndex = array_search('IN QTY', $this->columns);

                $totalInQty = 0;

                if ($inQtyColIndex !== false) {

                    $inQtyColLetter = Coordinate::stringFromColumnIndex(
                        $inQtyColIndex + 1
                    );

                    $totalInQty = (float) $sheet
                        ->getCell("{$inQtyColLetter}{$totalRow}")
                        ->getCalculatedValue();
                }                

                /*
                |--------------------------------------------------------------------------
                | Sum TOTAL per dynamic column
                |--------------------------------------------------------------------------
                */

                foreach (range(5, $columnCount) as $i) {

                    $colName = $this->columns[$i - 1] ?? '';

                    /*
                    |--------------------------------------------------------------------------
                    | SKIP IN QTY COLUMN
                    |--------------------------------------------------------------------------
                    */
                    if (strtoupper(trim($colName)) == 'IN QTY') {
                        continue;
                    }

                    $colLetter = Coordinate::stringFromColumnIndex($i);

                    /*
                    Convert:
                    PROCNAME_RWKNAME
                    into RWKNAME only
                    */
                    if (preg_match('/^(.*?)_(.*)$/', $colName, $match)) {

                        $rwkName = strtoupper(
                            str_replace('_', ' ', $match[2])
                        );

                    } else {

                        $rwkName = $colName;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | RWK TOTAL
                    |--------------------------------------------------------------------------
                    */

                    $rwkQty = (float) $sheet
                        ->getCell("{$colLetter}{$totalRow}")
                        ->getCalculatedValue();

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

                    $chartSheet->setCellValue("A{$chartRow}", $rwkName);
                    $chartSheet->setCellValue("B{$chartRow}", $totalValue);

                    $chartSheet->getStyle("B{$chartRow}")
                        ->getNumberFormat()
                        ->setFormatCode('0.00%');                    

                    $chartRow++;
                }

                /*
                |--------------------------------------------------------------------------
                | Auto Size
                |--------------------------------------------------------------------------
                */

                $chartSheet->getColumnDimension('A')->setAutoSize(true);
                $chartSheet->getColumnDimension('B')->setAutoSize(true);

                /*
                |--------------------------------------------------------------------------
                | Create Chart
                |--------------------------------------------------------------------------
                */

                $dataSeriesLabels = [
                    new DataSeriesValues(
                        DataSeriesValues::DATASERIES_TYPE_STRING,
                        "'RWK Chart'!\$B\$3",
                        null,
                        1
                    )
                ];

                $xAxisTickValues = [
                    new DataSeriesValues(
                        DataSeriesValues::DATASERIES_TYPE_STRING,
                        "'RWK Chart'!\$A\$4:\$A\$" . ($chartRow - 1),
                        null,
                        ($chartRow - 2)
                    )
                ];

                $dataSeriesValues = [
                    new DataSeriesValues(
                        DataSeriesValues::DATASERIES_TYPE_NUMBER,
                        "'RWK Chart'!\$B\$4:\$B\$" . ($chartRow - 1),
                        null,
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

                $title = new Title('REWORK RATIO CHART');

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

                $chart->setTopLeftPosition('D2');
                $chart->setBottomRightPosition('N20');

                /*
                |--------------------------------------------------------------------------
                | Add Chart
                |--------------------------------------------------------------------------
                */

                $chartSheet->addChart($chart);





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