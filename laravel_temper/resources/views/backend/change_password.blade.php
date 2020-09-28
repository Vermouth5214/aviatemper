<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Change Password';
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title')
	Change Password
@endsection

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left">
			<h3>Change Password</h3>
		</div>
		<div class="title_right">

        </div>
        <div class="clearfix"></div>
		@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	</div>
	<div class="clearfix"></div>
	<br/><br/>	
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_content">
					<?php
						if (Session::has('data')){
							$alert = "success";
							$data = Session::get('data');
							if ($data['status'] == false):
								$alert = "danger";
							endif;
					?>
							<div class="alert alert-<?=$alert;?> alert-dismissible fade show w-100" role="alert">					
								{{ $data['error'] }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							</div>
					<?php
						}
					?>				
					{{ Form::open(['class' => 'form-horizontal form-label-left']) }}
						{!! csrf_field() !!}
						<div class="item form-group">
							<label class="control-label col-sm-3">Old Password <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="password" name="old_pass" required="required" class="form-control" autofocus minlength=3 maxlength=15>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-sm-3">New Password <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="password" name="new_pass" required="required" class="form-control" minlength=3 maxlength=15>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-sm-3">Confirm New Password <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="password" name="confirm_new_pass" required="required" class="form-control" minlength=3 maxlength=15>
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="item form-group">
							<div class="col-sm-4 offset-sm-3 text-right" style="margin-top:10px;">
								<button type="submit" class="btn btn-primary btn-block">Submit </button>
							</div>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection

<!-- CSS -->
@section('css')

@endsection

<!-- JAVASCRIPT -->
@section('script')

@endsection