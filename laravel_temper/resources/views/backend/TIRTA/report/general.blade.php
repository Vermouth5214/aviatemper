 <?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Report';
    $breadcrumb[1]['url'] = url('backend/general-report');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'General Report')

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left">
			<h3>General Report</h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">

			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))	
	<div class="row">
		<div class="col-12">
			<div class="x_panel">
				<div class="x_content">
                    <form id="form-work" class="form-horizontal" role="form" autocomplete="off" method="GET">
                        <div class="row">
                            <div class="col-sm-2 text-right" style="margin-top:7px;">
                                Location
                            </div>
                            <div class="col-sm-3">
								{{
								Form::select(
									'location',
                                    $location_all,
									$location,
									array(
										'class' => 'form-control',
                                        'id' => 'location',
									))
								}}
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-12 col-sm-2 text-right" style="margin-top:7px;">
                                Date
                            </div>
                            <div class="col-12 col-sm-3 date">
                                <div class='input-group date' id='myDatepicker'>
                                    <input type='text' class="form-control" name="startDate" value=<?=$startDate;?> />
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 date">
                                <div class='input-group date' id='myDatepicker2'>
                                    <input type='text' class="form-control" name="endDate" value=<?=$endDate;?> />
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <input type="submit" class="btn btn-primary btn-block" name="submit" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>		
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-12">
			<div class="x_panel">
				<div class="x_content">
                    @include('backend.elements.notification')
                    <table class="table table-striped table-hover table-bordered dt-responsive dataTable" cellspacing="0" width="100%">
						<thead>
							<tr>
                                <th>Location</th>
                                <th>Date</th>
                                <th>Total</th>
								<th>Actions</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>					
	</div>
@endsection

<!-- CSS -->
@section('css')
    <!-- select2 -->
    <link href="<?=url('vendors/select2/dist/css/select2.min.css');?>" rel="stylesheet">
@endsection

<!-- JAVASCRIPT -->
@section('script')
    <script src="<?=url('vendors/select2/dist/js/select2.full.min.js');?>"></script>
	<script>
        $('#myDatepicker').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        $('#myDatepicker2').datetimepicker({
            format: 'DD-MM-YYYY'
        });
        $('#location').select2();
		$('.dataTable').dataTable({
			processing: true,
			serverSide: true,
			ajax: "<?=url('backend/general-reportt/datatable?location='.$location.'&startDate='.$startDate.'&endDate='.$endDate.'&mode='.$mode);?>",
			columns: [
				{data: 'lokasi', name: 'lokasi'},
				{data: 'created_at', name: 'created_at', className: "text-center"},
				{data: 'total', name: 'total', className: "text-right"},
				{data: 'action', name: 'action', orderable: false, searchable: false}
			],
			responsive: true,
			order : [[ 1, "asc" ]]
		});
	</script>
@endsection