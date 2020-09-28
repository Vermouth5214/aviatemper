<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Model\Lokasi;
use App\Model\Temperature;
use DB;

class ExportExcell implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct($lokasi, $tanggal)
    {
        $this->lokasi = $lokasi;
        $this->tanggal = $tanggal;
    }
    

    public function view(): View
    {
        $data  = Temperature::where('lokasi', $this->lokasi)->where(DB::raw('date(created_at)'), $this->tanggal )->orderBy('lokasi','ASC')->orderBy('created_at','ASC')->get();
        return view('exports.detail', [
            'data' => $data,
            'lokasi' => $this->lokasi,
            'tanggal' => $this->tanggal
        ]);           
    }   
}