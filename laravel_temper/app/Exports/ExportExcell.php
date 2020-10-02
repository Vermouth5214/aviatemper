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

        $data_pusat = [];
        $userinfo = Session::get('userinfo');
        if (($userinfo['priv'] == 'VTTIRTA') || ($userinfo['priv'] == 'VHTIRTA')){
            $data_pegawai = DB::connection('DB-ORANGE')->select("
                SELECT UPPER(NAMA) AS NAMA
                FROM View_IT_All_Karyawan_Aktif vka 
                LEFT JOIN cabang c ON vka.CABANG = c.Name 
                WHERE 
                    CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
                            WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
                            WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
                        END LIKE 'PUSAT%'
            ");
            foreach ($data_pegawai as $pegawai):
                array_push($data_pusat, $pegawai->NAMA);
            endforeach;
        }
    
        return view('exports.detail', [
            'data' => $data,
            'lokasi' => $this->lokasi,
            'tanggal' => $this->tanggal,
            'data_pusat' => $data_pusat
        ]);           
    }   
}