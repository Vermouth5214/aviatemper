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

use App\Exports\ExportExcell;
use Excel;

class ReportTController extends Controller {
	public function generalReport(Request $request) {
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

        view()->share('startDate', $startDate);
        view()->share('endDate', $endDate);
        view()->share('mode', $mode);
        view()->share('location', $location);
        view()->share('location_all', $location_all);

		return view ('backend.TIRTA.report.general');
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

		$userinfo = Session::get('userinfo');
		if (($userinfo['priv'] == 'VTTIRTA') || ($userinfo['priv'] == 'VHTIRTA')){
            if ((strtoupper($location) == "PUSAT")){
                $data = DB::connection('DB-ORANGE')->select("
                    SELECT UPPER(NAMA) AS NAMA
                    FROM View_IT_All_Karyawan_Aktif vka 
                    LEFT JOIN cabang c ON vka.CABANG = c.Name 
                    WHERE 
                        CASE WHEN VKA.CABANG LIKE '%DEAN%' THEN REPLACE(VKA.CABANG,' DEAN','') 
                                WHEN CABANG = 'JAKARTA SELATAN A' THEN 'JAKARTA SELATAN' 
                                WHEN CABANG = 'BOGOR A' THEN 'BOGOR' ELSE CABANG 
                          END LIKE 'PUSAT%'
                ");
                $query = $query . " and name in (";
                foreach ($data as $pegawai):
                    $query = $query . "'".$pegawai->NAMA."',";
                endforeach;
                $query = substr($query, 0, -1);
                $query = $query . ")";
            }
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

                $url = url('backend/general-reportt/'.$data->lokasi.'/'.$data->created_at);
                $url_export = url('backend/general-reportt/'.$data->lokasi.'/'.$data->created_at.'/export');

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
        $data_pusat = [];
        if (count($data)){
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

            view()->share('lokasi', $lokasi);
            view()->share('tanggal', $tanggal);
            view()->share('data_pusat', $data_pusat);
            return view ('backend.TIRTA.report.view', ['data' => $data]);
        }
		
    }

    public function export($lokasi, $tanggal){
        $data  = Temperature::where('lokasi', $lokasi)->where(DB::raw('date(created_at)'), $tanggal )->orderBy('lokasi','ASC')->orderBy('created_at','ASC')->get();
        if ($data->count()){
            return Excel::download(new ExportExcell($lokasi, $tanggal), $lokasi.'_'.date('d-m-Y H:i:s').'.xlsx');
        } else {
            return Redirect::to('/backend/general-reportt/')->with('success', "Invalid Data")->with('mode', 'danger');
        }
    }

}