<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PlanillaTitulacionExportsGonvanrri implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.planilla_titulacion_gonvanrri', [
            'data' => $this->data
        ]);
    }
}
