<?php

namespace App\Http\Controllers;

use Milon\Barcode\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Machine;

class MachineLabelController extends Controller
{
    public function printLabel(Machine $machine)
    {
        // Instantiate DNS1D
        $barcodeGenerator = new DNS1D();

        // Generate barcode as base64 image
        $barcode = $barcodeGenerator->getBarcodePNG($machine->mchn_cd, 'C39');

        $pdf = Pdf::loadView('machine.barcode-label', [
            'machine' => $machine,
            'barcode' => $barcode,
        ])
        ->setPaper([0, 0, 198.4, 141.7], 'portrait'); // width x height in points
        // ->setPaper([0, 0, 283, 198], 'portrait'); // 70x50 mm in points
        return $pdf->stream("barcode-{$machine->mchn_cd}.pdf");
    }

    public function printMultipleLabels()
    {
        $machines = Machine::all();

        $dns = new DNS1D();
        $dns->setStorPath(storage_path('framework/laravel-barcode'));

        foreach ($machines as $p) {
            $p->barcode = $dns->getBarcodePNG($p->mchn_cd, 'C39');
        }

        $pdf = Pdf::loadView('machine.barcode-label-multiple', [
            'machines' => $machines,
        ])
        ->setPaper([0, 0, 198.4, 141.7], 'portrait');

        return $pdf->stream('barcode-machine-all.pdf');        

    }    
}
