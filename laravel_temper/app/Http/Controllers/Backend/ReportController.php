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

class ReportController extends Controller {
	public function generalReport(Request $request) {
    	$startDate = date('d-m-Y');
        $endDate = date('d-m-Y');
        $mode = "limited";
        $location = "All";

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

        $location_all = Lokasi::where('active',1)->orderBy('kode_lokasi')->pluck('nama_lokasi','nama_lokasi')->prepend('ALL', 'ALL');
        
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
            SELECT lokasi, count(id) as total, date(created_at) as created_at FROM avia_temperature.temperature
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

}