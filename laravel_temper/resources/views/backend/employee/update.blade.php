<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Employee';
	$breadcrumb[1]['url'] = url('backend/employee');	
	$breadcrumb[2]['title'] = 'Add';
	$breadcrumb[2]['url'] = url('backend/employee/create');
	if (isset($data)){
		$breadcrumb[2]['title'] = 'Edit';
		$breadcrumb[2]['url'] = url('backend/employee/'.$data[0]->id.'/edit');
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
    Master Employee - <?=$mode;?>
@endsection

<!-- CONTENT -->
@section('content')
    <?php
		$nama = old('nama');
        $posisi = old('posisi');
		$active = old('active');
		$method = "POST";
		$mode = "Create";
		$url = "backend/employee/";
		if (isset($data)){
			$nama = $data[0]->nama;
            $posisi = $data[0]->posisi;
			$active = $data[0]->active;
			$method = "PUT";
			$mode = "Edit";
			$url = "backend/employee/".$data[0]->id;
		}
	?>
	<div class="page-title">
		<div class="title_left">
			<h3>Master Employee - <?=$mode;?></h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-8 form-group pull-right top_search">
                @include('backend.elements.back_button',array('url' => '/backend/employee'))
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
							<label class="control-label col-sm-3">Name <span class="required">*</span></label>
							<div class="col-sm-5">
								<input type="text" name="nama" required="required" class="form-control" value="<?=$nama;?>" autofocus>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-sm-3 col-xs-12">Position </label>
							<div class="col-sm-3 col-12">
								{{
								Form::select(
									'posisi',
									['SECURITY' => 'SECURITY', 'GARDENER' => 'GARDENER', 'AC' => 'AC', 'CLEANING SERVICE' => 'CLEANING SERVICE' , 'GONDOLA' => 'GONDOLA', 'PEST CONTROL' => 'PEST CONTROL', 'DRIVER' => 'DRIVER'],
									$posisi,
									array(
										'class' => 'form-control',
									))
								}}
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-sm-3">Status</label>
							<div class="col-sm-3">
								{{
								Form::select(
									'active',
									['1' => 'Active', '2' => 'Non Active'],
									$active,
									array(
										'class' => 'form-control',
									))
								}}								
							</div>
						</div>	
						<div class="ln_solid"></div>
						<div class="item form-group">
							<div class="col-sm-6 offset-sm-3">
								<a href="<?=url('/backend/employee')?>" class="btn btn-warning">Cancel</a>
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

@endsection

<!-- JAVASCRIPT -->
@section('script')

@endsection