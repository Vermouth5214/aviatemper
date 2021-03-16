<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Model\Lokasi;
use App\Model\Temperature;
use DB;
use Session;

class ExportExcellActive implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct($lokasi, $tanggal, $data_aktif, $data_temper, $aktif_is_temper)
    {
        $this->lokasi = $lokasi;
        $this->tanggal = $tanggal;
        $this->data_aktif = $data_aktif;
        $this->data_temper = $data_temper;
        $this->aktif_is_temper = $aktif_is_temper;
    }
    

    public function view(): View
    {
        return view('exports.detail-view', [
            'lokasi' => $this->lokasi,
            'tanggal' => $this->tanggal,
            'data_aktif' => $this->data_aktif,
            'data_temper' => $this->data_temper,
            'aktif_is_temper' => $this->aktif_is_temper
        ]);           
    }   
}