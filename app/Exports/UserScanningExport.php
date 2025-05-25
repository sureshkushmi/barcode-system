<?php

namespace App\Exports;
use App\Http\Controllers\ReportController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserScanningExport implements FromView
{
    protected $shipments;

    public function __construct($shipments)
    {
        $this->shipments = $shipments;
    }

    public function view(): View
    {
        return view('exports.user_scanning', [
            'shipments' => $this->shipments
        ]);
    }
}
