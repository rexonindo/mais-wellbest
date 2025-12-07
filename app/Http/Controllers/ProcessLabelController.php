<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Process;


class ProcessLabelController extends Controller
{
    public function printLabel(Process $process)
    {
        // Instantiate DNS1D
        $barcodeGenerator = new DNS1D();

        // Generate barcode as base64 image
        $barcode = $barcodeGenerator->getBarcodePNG($process->proc_cd, 'C39');

        $pdf = Pdf::loadView('process.barcode-label', [
            'process' => $process,
            'barcode' => $barcode,
        ])
        ->setPaper([0, 0, 198.4, 141.7], 'portrait'); // width x height in points
        // ->setPaper([0, 0, 283, 198], 'portrait'); // 70x50 mm in points
        return $pdf->stream("barcode-process-{$process->proc_cd}.pdf");
    }

    public function printMultipleLabels()
    {
        $processes = Process::all();

        $dns = new DNS1D();
        $dns->setStorPath(storage_path('framework/laravel-barcode'));

        foreach ($processes as $p) {
            $p->barcode = $dns->getBarcodePNG($p->proc_cd, 'C39');
        }

        $pdf = Pdf::loadView('process.barcode-label-multiple', [
            'processes' => $processes,
        ])
        ->setPaper([0, 0, 198.4, 141.7], 'portrait');

        return $pdf->stream('barcode-process-all.pdf');        

    }



}
