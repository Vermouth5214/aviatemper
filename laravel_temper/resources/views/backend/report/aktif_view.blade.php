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
    Active Employee Report
@endsection

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left">
			<h3>Active Employee Report</h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
				@include('backend.elements.back_button',array('url' => '/backend/active-employee-report'))
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
							<h3>Karyawan Aktif belum cek temperature</h3>
							<div class="table-responsive">
								<table class="table table-striped table-hover table-bordered dt-responsive dataTable" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>No</th>
										<th>NIK</th>
										<th>NAMA</th>
										<th>JABATAN</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										foreach ($data_aktif as $item):
									?>
											<tr>
												<td align='right'><?=$i;?></td>
												<td ><?=$item->NIK?></td>
												<td ><?=$item->NAMA;?></td>
												<td ><?=$item->JABATAN;?></td>
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
					<br/>
					<div class="row">
						<div class="col-12">
							<h3>Karyawan Aktif sudah cek temperature</h3>
							<div class="table-responsive">
								<table class="table table-striped table-hover table-bordered dt-responsive dataTable" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>No</th>
										<th>NIK</th>
										<th>NAMA</th>
										<th>JABATAN</th>
                                        <th>Temperature</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										foreach ($aktif_is_temper as $item):
									?>
											<tr>
												<td align='right'><?=$i;?></td>
												<td ><?=$item['NIK']?></td>
												<td ><?=$item['NAMA'];?></td>
												<td ><?=$item['JABATAN'];?></td>
                                                <td ><?=$item['TEMPER'];?></td>
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
					<br/>
					<div class="row">
						<div class="col-12">
							<h3>Bukan Karyawan Aktif</h3>
							<div class="table-responsive">
								<table class="table table-striped table-hover table-bordered dt-responsive dataTable" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>No</th>
										<th>NIK</th>
										<th>NAMA</th>
                                        <th>Temperature</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										foreach ($data_temper as $item):
									?>
											<tr>
												<td align='right'><?=$i;?></td>
												<td ><?=$item['NIK']?></td>
												<td ><?=$item['NAMA'];?></td>
                                                <td ><?=$item['TEMPER'];?></td>
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