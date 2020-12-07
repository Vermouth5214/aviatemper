<?php

namespace App\Imports;

use Session;
use App\Model\Temperature;
use App\Model\Lokasi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InjectData implements ToCollection, WithMultipleSheets
{
    private $error = [];

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }    

    public function collection(Collection $rows)
    {
        $i = 1;
        foreach ($rows as $row) 
        {
            $cek_lokasi = Lokasi::where('kode_lokasi', trim($row[0]))->get();
            if (count($cek_lokasi) == 0){
                $text = "Baris ".$i." : Kode Lokasi tidak ditemukan";
                array_push($this->error,$text);
            } else {
                $data = new Temperature;
                $data->lokasi = $cek_lokasi[0]->nama_lokasi;
                $data->nik = trim($row[1]);
                $data->name = trim($row[2]);
                $data->temperature = trim($row[3]);
                $data->created_at = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]);
                $data->created_by = Session::get('userinfo')['username'];
                $data->save();
            }
            $i++;
        }
    }

    public function getError(): array
    {
        return $this->error;
    }    

}