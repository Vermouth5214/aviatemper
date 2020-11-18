<?php

namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use DB;
use Redirect;
use App\Model\Temperature;
use App\Model\UserLogin;
use App\Model\Lokasi;

class InputAdminController extends Controller {
	public function index(Request $request) {
		$lokasi = Lokasi::where('active',1)->orderBy('kode_lokasi')->pluck('nama_lokasi','nama_lokasi');
		$lokasi_pilih = "PUSAT";
		//ambil data orange
		$data = DB::connection('DB-ORANGE')->select("
			SELECT c.Code AS [KODE CABANG], 
				CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
						WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
						WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
				END AS CABANG, 
				NIK, UPPER(NAMA) AS NAMA, (NIK + '-' + UPPER(NAMA)) AS NIKNAMA,
				(NIK + ' - ' + UPPER(CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
				WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
				WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
			END) + ' - ' + UPPER(NAMA)) AS NIKNAMACABANG
			FROM View_IT_All_Karyawan_Aktif vka 
			LEFT JOIN cabang c ON vka.CABANG = c.Name 
			WHERE 
				CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
						WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
						WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
					END  = '".$lokasi_pilih."' OR 
				CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
						WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
						WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
				END LIKE 'PUSAT%'
			ORDER BY c.Code DESC
		");
		$data = collect($data)->pluck('NIKNAMACABANG','NIKNAMA')->prepend('','');

		view()->share('lokasi', $lokasi);
		view()->share('data', $data);
		return view ('backend.TIRTA.input');
	}

	public function search($id){
		$data = [
			'status' => false,
			'data' => '',
		];

		$pegawai = DB::connection('DB-ORANGE')->select("
			SELECT c.Code AS [KODE CABANG], 
				CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
						WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
						WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
				END AS CABANG, 
				NIK, UPPER(NAMA) AS NAMA, (NIK + '-' + UPPER(NAMA)) AS NIKNAMA,
				(NIK + ' - ' + UPPER(CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
				WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
				WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
			END) + ' - ' + UPPER(NAMA)) AS NIKNAMACABANG
			FROM View_IT_All_Karyawan_Aktif vka 
			LEFT JOIN cabang c ON vka.CABANG = c.Name 
			WHERE 
				CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
						WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
						WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
					END  = '".$id."' OR 
				CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
						WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
						WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
				END LIKE 'PUSAT%'
			ORDER BY c.Code DESC
		");		

		$text = "";
		foreach ($pegawai as $pegawai_isi):
			$text.="<option value='".$pegawai_isi->NIKNAMA."'>".$pegawai_isi->NIKNAMACABANG."</option>";
		endforeach;
	
		if (count($pegawai)){
			$data['status'] = true;
			$data['data'] = $text;
		}
		return $data;
	}

	public function store(Request $request) {
		$data = [
			'status' => false,
		];

		$insert = true;
		$mode = $request->mode;
		$userinfo = Session::get('userinfo' );
		
		$lokasi = $request->lokasi;
		$temperature = $request->temperature;
		$created_by = $userinfo['username'];
		$nik = '';
		$card_no = '';
		$name = '';

		$mode = "name";

		if ($mode == "name"){
			$input = explode("-",$request->search_name);
			$nik = $input[0];
			$name = $input[1];
		}

		if ($insert){
			$data = new Temperature();
			$data->lokasi = $lokasi;
			$data->card_no = $card_no;
			$data->nik = $nik;
			$data->name = $name;
			$data->temperature = $temperature;
			$data->created_by = $created_by;
			$data->created_at = date('Y-m-d', strtotime($request->date_check))." 00:00:00";
			if($data->save()){
				$data['status'] = true;
			}
		} else {
			$data['status'] = false;
		}
		return $data;
	}

}