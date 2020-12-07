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

class InputController extends Controller {
	public function index(Request $request) {
		$userinfo = Session::get('userinfo');
		if (($userinfo['priv'] == 'VTTIRTA') || ($userinfo['priv'] == 'VHTIRTA')){
			return redirect('/backend/general-reportt');
		}

		//CEK JIKA USER TIRTA MENGGUNAKAN PASSWORD DEFAULT
		$user = UserLogin::where('username',$userinfo['username'])->get();
		if (($userinfo['priv'] == "USER") && ($userinfo['pt'] == "TIRTA") && (md5('12345') == $user[0]->password)) {
			return redirect('/backend/change-password');
		}

		$mode = "";
		$lokasi = $userinfo['lokasi'];
		if (substr($lokasi,0,5) == "PUSAT"){
			//ambil data pusat
			// $data = DB::connection('DB-PUSAT')->select("
			// 	SELECT max(bcs.[CARDNO]) AS CARDNO, bcs.[FNAME], (max(bcs.[CARDNO]) + '-' + bcs.[FNAME]) AS FNAMEBCA
			// 	FROM [BADGE_CARD_SEARCH] bcs, [BADGE_CARD_ALL] bca
			// 	where bcs.[CARDNO] = bca.[CARDNO] 
			// 	and [COMPANY_NAM] not like 'AIC%' and [COMPANY_NAM] <> 'LobbyWorks Visitors' and [COMPANY_NAM] not like 'PEMASOK'
			// 	GROUP BY bcs.[FNAME]
			// ");

			//ambil data security 
			$data = DB::select("
				SELECT CONCAT(nik,'-',nama) as FNAMEBCA, nama as FNAME
				FROM karyawan
				WHERE active = 1
				ORDER BY nama ASC
			");

			$data = collect($data)->pluck('FNAME','FNAMEBCA')->prepend('','');

			$mode = "PUSAT";
		} else {
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
				END) + ' - ' + UPPER(NAMA) + ' - ' + UPPER(JABATAN)) AS NIKNAMACABANG					
				FROM View_IT_All_Karyawan_Aktif vka 
				LEFT JOIN cabang c ON vka.CABANG = c.Name 
				WHERE 
					CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
						 WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
						 WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
					   END  = '".$lokasi."' OR 
					CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
				   		 WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
				   		 WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
			  		END LIKE 'PUSAT%'
				ORDER BY c.Code DESC
			");
			$data = collect($data)->pluck('NIKNAMACABANG','NIKNAMA')->prepend('','');
			$mode = "CABANG";
		}

		view()->share('data', $data);
		view()->share('mode', $mode);
		return view ('backend.input');
	}

	public function search($id){
		$data = [
			'status' => false,
			'number' => '',
			'name' => '',
		];

		$number = ltrim($id, '0');
		
		$pegawai = DB::connection('DB-PUSAT')->select("
			SELECT bcs.[CARDNO], bcs.[FNAME]
			FROM [BADGE_CARD_SEARCH] bcs, [BADGE_CARD_ALL] bca
			where bcs.[CARDNO] = bca.[CARDNO] 
			and [COMPANY_NAM] not like 'AIC%' and [COMPANY_NAM] <> 'LobbyWorks Visitors' and [COMPANY_NAM] not like 'PEMASOK' and bcs.[CARDNO] = '".$number."'
		");

		if (count($pegawai)){
			$data['status'] = true;
			$data['number'] = $id;
			$data['name'] = $pegawai[0]->FNAME;
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
		
		$lokasi = $userinfo['lokasi'];
		$temperature = $request->temperature;
		$created_by = $userinfo['username'];
		$nik = '';
		$card_no = '';
		$name = '';

		if ($lokasi <> "PUSAT"){
			$mode = "name";
		}

		if ($mode == "card"){
			$number = ltrim($request->search_number_real, '0');
		
			$pegawai = DB::connection('DB-PUSAT')->select("
				SELECT bcs.[CARDNO], bcs.[FNAME]
				FROM [BADGE_CARD_SEARCH] bcs, [BADGE_CARD_ALL] bca
				where bcs.[CARDNO] = bca.[CARDNO] 
				and [COMPANY_NAM] not like 'AIC%' and [COMPANY_NAM] <> 'KELUARGA' and [COMPANY_NAM] <> 'LobbyWorks Visitors' and [COMPANY_NAM] not like 'PEMASOK' and bcs.[CARDNO] = '".$number."'
			");
	
			if (count($pegawai)){
				$card_no = $number;
				$name = $pegawai[0]->FNAME;
			} else {
				$insert = false;
			}
		} else 
		if ($mode == "name"){
			$input = explode("-",$request->search_name);
			if ($lokasi == "PUSAT"){
				$card_no = $input[0];
			} else {
				$nik = $input[0];
			}
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
			if($data->save()){
				$data['status'] = true;
			}
		} else {
			$data['status'] = false;
		}
		return $data;
	}

}