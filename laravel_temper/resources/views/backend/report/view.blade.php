<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'General Report';
	$breadcrumb[1]['url'] = url('backend/general-report/'.$lokasi.'/'.$tanggal.'/join');	
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title')
    General Report
@endsection

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left">
			<h3>General Report</h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
				<a href="<?=url('/backend/general-report');?>" class="btn-index btn btn-primary btn-block" title="Back"><i class="fa fa-arrow-left"></i>&nbsp; Back</a>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	<div class="row">
		<div class="col-12">
			<div class="x_panel">
				<div class="x_content">
					<div class="row">
						<div class="col-12">
							<h4>Location : <b><i><?=$lokasi;?></i></b></h4>
							<?php
								$date = date('d F Y', strtotime($tanggal));
							?>
							<h4>Date : <b><i><?=$date;?></i></b></h4>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="table-responsive">
								<table class="table table-striped table-hover table-bordered dt-responsive dataTable" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>No</th>
										<th>Name</th>
                                        <th>Temperature</th>
                                        <th>Time</th>
										<th>Checked By</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
                                        foreach ($data as $item):
									?>
											<tr>
												<td align='right' ><?=$i;?></td>
												<td ><?=$item->name;?></td>
                                                <td ><?=$item->temperature;?></td>
                                                <td ><?=date('H:i:s', strtotime($item->created_at));?></td>
												<td ><?=$item->created_by;?></td>
											</tr>
									<?php
											$i++;
										endforeach;
									?>
								</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

<!-- CSS -->
@section('css')
	<style>
		.red{
			color : red;
		}
	</style>
@endsection

<!-- JAVASCRIPT -->
@section('script')

@endsection