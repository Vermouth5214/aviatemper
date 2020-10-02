<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'User';
	$breadcrumb[1]['url'] = url('backend/user');	
	$breadcrumb[2]['title'] = 'Add';
	$breadcrumb[2]['url'] = url('backend/user/create');
	if (isset($data)){
		$breadcrumb[2]['title'] = 'Edit';
		$breadcrumb[2]['url'] = url('backend/user/'.$data[0]->id.'/edit');
	}
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title')
	<?php
		$mode = "Create";
		if (isset($data)){
			$mode = "Edit";
		}
	?>
    Master User - <?=$mode;?>
@endsection

<!-- CONTENT -->
@section('content')
    <?php
		if (isset($data)){
			$tipe = $data[0]->tipe;
			$user = $data[0]->user;
            $username = $data[0]->username;
			$user_level = $data[0]->user_level;
			$name = $data[0]->name;
			$method = "PUT";
			$mode = "Edit";
			$url = "backend/usert/".$data[0]->id;
		}
	?>
	<div class="page-title">
		<div class="title_left">
			<h3>Master User - <?=$mode;?></h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
                @include('backend.elements.back_button',array('url' => '/backend/usert'))
			</div>
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
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
                            </ul>
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						</div>
					@endif				
					{{ Form::open(['url' => $url, 'method' => $method,'class' => 'form-horizontal form-label-left']) }}
						{!! csrf_field() !!}
						<div class="item form-group">
							<label class="control-label col-sm-3 col-xs-12">Username <span class="required">*</span></label>
							<div class="col-sm-3">
								<?=$username;?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-sm-3 col-xs-12">Level </label>
							<div class="col-sm-3">
								<?php
									if ($user_level == "VSUPER"){
										echo "SUPER ADMIN";
									} else 
									if ($user_level == "VADM"){
										echo "ADMIN";
									} else 
									if ($user_level == "VTTIRTA"){
										echo "IT TIRTA";
									} else 
									if ($user_level == "VHTIRTA"){
										echo "HRD TIRTA";
									} else {
										echo $user_level;
									}
								?>
							</div>
                        </div>
						<div class="item form-group">
							<label class="control-label col-sm-3 col-xs-12">Name <span class="required">*</span></label>
							<div class="col-sm-4 col-xs-12">
								<input type="text" name="name" required="required" class="form-control" value="<?=$name;?>">
							</div>
						</div>
						<?php
							if ($mode == "Edit"):
						?>
						<div class="item form-group">
							<label class="control-label col-sm-3 col-xs-12">Password<br/><small>default 12345</small></label>
							<div class="col-sm-4 col-xs-12">
								<input type="password" id="password" name="pwd" class="form-control d-none">
								<input type="checkbox" id="password_check" name="password_check" value="1">
								Change Password
							</div>
						</div>
						<?php
							endif;
						?>
						<div class="item form-group">
							<label class="control-label col-sm-3 col-xs-12">Lokasi </label>
							<div class="col-sm-3 col-xs-12">
								<?=$lokasi->nama_lokasi;?>
							</div>
                        </div>
						<div class="ln_solid"></div>
						<div class="item form-group">
							<div class="col-sm-6 col-xs-12 offset-sm-3">
								<a href="<?=url('/backend/usert')?>" class="btn btn-warning">Cancel</a>
								<button type="submit" class="btn btn-primary">Submit </button>
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
    <!-- select2 -->
    <link href="<?=url('vendors/select2/dist/css/select2.min.css');?>" rel="stylesheet">
@endsection

<!-- JAVASCRIPT -->
@section('script')
	<script src="<?=url('vendors/select2/dist/js/select2.min.js');?>"></script>
	<script>
		$('#lokasi').select2();
		$("#password_check").on("change", function(){
			if($(this).prop('checked') == true){
				$("#password").removeClass("d-none");
				$("#password").prop('required',true);
			} else {
				$("#password").addClass("d-none");
				$("#password").prop('required',false);
			}
		});
	</script>
@endsection