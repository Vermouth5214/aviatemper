<?php
namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use DB;
use Redirect;
use App\Model\Lokasi;
use App\Model\Temperature;
use Datatables;
use App\Model\UserLogin;

use App\Exports\ExportExcell;
use App\Exports\ExportExcellActive;
use Excel;

class ReportController extends Controller {
	public function generalReport(Request $request) {
        $userinfo = Session::get('userinfo');
        if (($userinfo['priv'] == "USER") && ($userinfo['priv'] == "TIRTA")){
            return redirect('/backend/input');
        }

		//CEK JIKA USER TIRTA MENGGUNAKAN PASSWORD DEFAULT
		$user = UserLogin::where('username',$userinfo['username'])->get();
		if (($userinfo['priv'] == "USER") && ($userinfo['pt'] == "TIRTA") && (md5('12345') == $user[0]->password)) {
			return redirect('/backend/change-password');
		}

    	$startDate = date('d-m-Y');
        $endDate = date('d-m-Y');
        $mode = "limited";
        $location = "ALL";

        if (isset($_GET["startDate"]) || isset($_GET["endDate"]) || isset($_GET["status"]) || isset($_GET["mode"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
            }
			if (isset($_GET["mode"])){
				$mode = $_GET['mode'];
            }
			if (isset($_GET["location"])){
				$location = $_GET['location'];
            }
        }

        $location_all = Lokasi::where('active',1)->orderBy('kode_lokasi')->pluck('nama_lokasi','nama_lokasi')->prepend('ALL','ALL');
        
		$userinfo = Session::get('userinfo' );
        $lokasi = $userinfo['lokasi'];
        if ($userinfo['priv'] != "VSUPER"){
            $location = $lokasi;
        }

        view()->share('startDate', $startDate);
        view()->share('endDate', $endDate);
        view()->share('mode', $mode);
        view()->share('location', $location);
        view()->share('location_all', $location_all);

		return view ('backend.report.general');
	}

	public function datatable() {
    	$startDate = "01"."-".date('m-Y');
        $endDate = date('d-m-Y');
        $mode = "limited";
        $location = 'All';

        if (isset($_GET["startDate"]) || isset($_GET["endDate"]) || isset($_GET["status"]) || isset($_GET["mode"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
            }
			if (isset($_GET["mode"])){
				$mode = $_GET['mode'];
            }
			if (isset($_GET["location"])){
				$location = $_GET['location'];
            }
        }

        $query = '
            SELECT lokasi, count(id) as total, date(created_at) as created_at FROM temperature
        ';
        if ($mode != "all"){
            $query = $query . " where ((created_at >= '".date('Y-m-d 00:00:00',strtotime($startDate)). "' and created_at <= '".date('Y-m-d 23:59:59',strtotime($endDate))."'))";
        }

        if (strtoupper($location) != "ALL"){
            $query = $query . " and lokasi='".$location."'";
        }

        $query = $query . " group by lokasi, date(created_at)";

        $data = collect(DB::connection('mysql')
                    ->select($query));

        return Datatables::of($data)
            ->editColumn('created_at', function($data) {
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->addColumn('action', function ($data) {
				$segment =  \Request::segment(2);

                $url = url('backend/general-report/'.$data->lokasi.'/'.$data->created_at);
                $url_export = url('backend/general-report/'.$data->lokasi.'/'.$data->created_at.'/export');

                $view = "<a class='btn-action btn btn-primary' href='".$url."' title='View'><i class='fa fa-eye'></i></a>";
                $export = "<a class='btn-action btn btn-success' href='".$url_export."' title='Export'>Export</a>";

				return $view." ".$export;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($lokasi, $tanggal)
    {
        //
        $data  = Temperature::where('lokasi', $lokasi)->where(DB::raw('date(created_at)'), $tanggal )->orderBy('lokasi','ASC')->orderBy('created_at','ASC')->get();
        if (count($data)){
            view()->share('lokasi', $lokasi);
            view()->share('tanggal', $tanggal);
            return view ('backend.report.view', ['data' => $data]);
        }
		
    }

    public function export($lokasi, $tanggal){
        $data  = Temperature::where('lokasi', $lokasi)->where(DB::raw('date(created_at)'), $tanggal )->orderBy('lokasi','ASC')->orderBy('created_at','ASC')->get();
        if ($data->count()){
            return Excel::download(new ExportExcell($lokasi, $tanggal), $lokasi.'_'.date('d-m-Y H:i:s').'.xlsx');
        } else {
            return Redirect::to('/backend/general-report/')->with('success', "Invalid Data")->with('mode', 'danger');
        }
    }


	public function activeEmployeeReport(Request $request) {
        $userinfo = Session::get('userinfo');

        $startDate = date('d-m-Y');
        $endDate = date('d-m-Y');
        $mode = "limited";
        $location = "PUSAT";

        if (isset($_GET["startDate"]) || isset($_GET["endDate"]) || isset($_GET["status"]) || isset($_GET["mode"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
            }
			if (isset($_GET["mode"])){
				$mode = $_GET['mode'];
            }
			if (isset($_GET["location"])){
				$location = $_GET['location'];
            }
        }

        $location_all = Lokasi::where('active',1)->orderBy('kode_lokasi')->pluck('nama_lokasi','nama_lokasi');
        
		$userinfo = Session::get('userinfo' );
        $lokasi = $userinfo['lokasi'];
        if (($userinfo['priv'] != "VSUPER") && ($userinfo['priv'] != "VHTIRTA") && ($userinfo['priv'] != "VTTIRTA")) {
            $location = $lokasi;
        }

        view()->share('startDate', $startDate);
        view()->share('endDate', $endDate);
        view()->share('mode', $mode);
        view()->share('location', $location);
        view()->share('location_all', $location_all);

		return view ('backend.report.active');
    }
    

	public function activeEmployee_datatable() {
    	$startDate = "01"."-".date('m-Y');
        $endDate = date('d-m-Y');
        $mode = "limited";
        $location = 'All';

        if (isset($_GET["startDate"]) || isset($_GET["endDate"]) || isset($_GET["status"]) || isset($_GET["mode"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
            }
			if (isset($_GET["mode"])){
				$mode = $_GET['mode'];
            }
			if (isset($_GET["location"])){
				$location = $_GET['location'];
            }
        }

        $query = '
            SELECT lokasi, count(distinct(name)) as total, date(created_at) as created_at FROM temperature
        ';
        if ($mode != "all"){
            $query = $query . " where ((created_at >= '".date('Y-m-d 00:00:00',strtotime($startDate)). "' and created_at <= '".date('Y-m-d 23:59:59',strtotime($endDate))."'))";
        }

        if (strtoupper($location) != "ALL"){
            $query = $query . " and lokasi='".$location."'";
        }

        $query = $query . " group by lokasi, date(created_at)";

        $data = collect(DB::connection('mysql')
                    ->select($query));

        return Datatables::of($data)
            ->editColumn('total', function($data) {
                $total_aktif = 0;
                $total_suhu = $data->total;
                $aktif = DB::connection('DB-ORANGE')->select("
                    SELECT count(NIK) as total_aktif
                    FROM View_IT_All_Karyawan_Aktif vka 
                    LEFT JOIN cabang c ON vka.CABANG = c.Name 
                    WHERE 
                        CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
                            WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
                            WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
                        END  = '".$data->lokasi."'
                    GROUP BY c.Code
                ");
                $aktif = collect($aktif);
                if (count($aktif)){
                    $total_aktif = $aktif[0]->total_aktif;
                }
                return $total_suhu ." / ".$total_aktif;
                
            })
            ->editColumn('created_at', function($data) {
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->addColumn('action', function ($data) {
				$segment =  \Request::segment(2);

                $url = url('backend/active-employee-report/'.$data->lokasi.'/'.$data->created_at);
                $url_export = url('backend/active-employee-report/'.$data->lokasi.'/'.$data->created_at.'/export');

                $view = "<a class='btn-action btn btn-primary' href='".$url."' title='View'><i class='fa fa-eye'></i></a>";
                $export = "<a class='btn-action btn btn-success' href='".$url_export."' title='Export'>Export</a>";

				return $view." ".$export;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show_active_employee($lokasi, $tanggal)
    {
        //
        $data_temper  = Temperature::select([DB::raw('nik as NIK'), DB::raw('name as NAMA'), DB::raw('max(temperature) as TEMPER')])->where('lokasi', $lokasi)->where(DB::raw('date(created_at)'), $tanggal )->groupBy(['nik','name'])->orderBy('name','ASC')->get()->toArray();
        $data_aktif = DB::connection('DB-ORANGE')->select("
            SELECT NIK, UPPER(NAMA) AS NAMA, UPPER(JABATAN) AS JABATAN
            FROM View_IT_All_Karyawan_Aktif vka 
            LEFT JOIN cabang c ON vka.CABANG = c.Name 
            WHERE 
                CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
                    WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
                    WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
                END  = '".$lokasi."'
        ");
        $data_aktif = collect($data_aktif)->toArray();

        $aktif_not_temper = [];
        $temper_not_aktif = [];
        $aftif_is_temper = [];

        $ctr = 0;
        foreach($data_aktif as $key_aktif=>$aktif) :
            foreach($data_temper as $key_temper=>$temper) :
                if ($aktif->NIK == $temper['NIK']){
                    $aktif_is_temper[$ctr]['NIK'] = $temper['NIK'];
                    $aktif_is_temper[$ctr]['NAMA'] = $temper['NAMA'];
                    $aktif_is_temper[$ctr]['JABATAN'] = $aktif->JABATAN;
                    $aktif_is_temper[$ctr]['TEMPER'] = $temper['TEMPER'];
                    $ctr++;
                    unset($data_aktif[$key_aktif]);
                    unset($data_temper[$key_temper]);
                }
            endforeach;
        endforeach;

        view()->share('lokasi', $lokasi);
        view()->share('tanggal', $tanggal);
        return view ('backend.report.aktif_view', ['data_aktif' => $data_aktif, 'data_temper' => $data_temper, 'aktif_is_temper' => $aktif_is_temper]);
    }


    public function export_active_employee($lokasi, $tanggal){
        $data_temper  = Temperature::select([DB::raw('nik as NIK'), DB::raw('name as NAMA'), DB::raw('max(temperature) as TEMPER')])->where('lokasi', $lokasi)->where(DB::raw('date(created_at)'), $tanggal )->groupBy(['nik','name'])->orderBy('name','ASC')->get()->toArray();
        $data_aktif = DB::connection('DB-ORANGE')->select("
            SELECT NIK, UPPER(NAMA) AS NAMA, UPPER(JABATAN) AS JABATAN
            FROM View_IT_All_Karyawan_Aktif vka 
            LEFT JOIN cabang c ON vka.CABANG = c.Name 
            WHERE 
                CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
                    WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
                    WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
                END  = '".$lokasi."'
        ");
        $data_aktif_check = $data_aktif;
        $data_aktif = collect($data_aktif)->toArray();

        $aktif_not_temper = [];
        $temper_not_aktif = [];
        $aftif_is_temper = [];

        $ctr = 0;
        foreach($data_aktif as $key_aktif=>$aktif) :
            foreach($data_temper as $key_temper=>$temper) :
                if ($aktif->NIK == $temper['NIK']){
                    $aktif_is_temper[$ctr]['NIK'] = $temper['NIK'];
                    $aktif_is_temper[$ctr]['NAMA'] = $temper['NAMA'];
                    $aktif_is_temper[$ctr]['JABATAN'] = $aktif->JABATAN;
                    $aktif_is_temper[$ctr]['TEMPER'] = $temper['TEMPER'];
                    $ctr++;
                    unset($data_aktif[$key_aktif]);
                    unset($data_temper[$key_temper]);
                }
            endforeach;
        endforeach;

        if (count($data_aktif_check)){
            return Excel::download(new ExportExcellActive($lokasi, $tanggal, $data_aktif, $data_temper, $aktif_is_temper), $lokasi.'_'.date('d-m-Y H:i:s').'.xlsx');
        } else {
            return Redirect::to('/backend/active-employee-report/')->with('success', "Invalid Data")->with('mode', 'danger');
        }
    }


}