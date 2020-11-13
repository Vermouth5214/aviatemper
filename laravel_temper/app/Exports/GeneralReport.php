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

class GeneralReport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct($startDate, $endDate, $mode, $location)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->mode = $mode;
        $this->location = $location;

    }
    

    public function view(): View
    {
        $data = new Temperature();
        if (strtoupper($this->location) != "ALL"){
            $data = $data->where('lokasi', $this->location);
        }
        if ($this->mode != "all"){
            $data = $data->where('created_at','>=',date('Y-m-d 00:00:00',strtotime($this->startDate)))->where('created_at','<=',date('Y-m-d 23:59:59',strtotime($this->endDate)));
        }

        $data = $data->orderBy('lokasi','ASC')->orderBy('created_at','ASC')->get();

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
    
        return view('exports.detail_tirta', [
            'data' => $data,
            'location' => $this->location,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'mode' => $this->mode,
            'data_pusat' => $data_pusat
        ]);           
    }   
}