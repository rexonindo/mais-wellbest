<?php

namespace App\Http\Controllers;

use Milon\Barcode\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\WorkOrder;

class WorkOrderLabelController extends Controller
{
    public function printLabel(WorkOrder $workOrder)
    {
        // Instantiate DNS1D
        $barcodeGenerator = new DNS1D();

        // Generate barcode as base64 image
        $barcode = $barcodeGenerator->getBarcodePNG($workOrder->wo_no, 'C39');

        $pdf = Pdf::loadView('workorders.barcode-label', [
            'workOrder' => $workOrder,
            'barcode' => $barcode,
        ])
        ->setPaper([0, 0, 198.4, 141.7], 'portrait'); // width x height in points
        // ->setPaper([0, 0, 283, 198], 'portrait'); // 70x50 mm in points
        return $pdf->stream("barcode-{$workOrder->wo_no}.pdf");
    }
}
