<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Inject Data';
    $breadcrumb[1]['url'] = url('backend/inject-data');
	$breadcrumb[2]['title'] = 'Upload';
	$breadcrumb[2]['url'] = url('backend/inject-data/upload');
    
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Inject Data')

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left">
			<h3>Inject Data</h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">

			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))	
	<div class="row">
		<div class="col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                    @include('backend.elements.notification')
                    @if (Session::has('error'))
                        <?php
                            if (!empty(Session::get('error'))) :
                        ?>
                        <div class="row">
                            <div class="col-12 alert alert-danger alert-dismissible" role="alert">
                                <?php
                                    foreach (Session::get('error') as $error):
                                        echo $error."<br/>";
                                    endforeach;
                                ?>
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            </div>
                        </div>
                        <?php
                            endif;
                        ?>
                    @endif
                    {{ Form::open(['url' => 'backend/inject-data', 'method' => 'POST','class' => 'form-horizontal', 'files' => true]) }}
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4 col-12">Upload file : <span class="required">*</span><br/>
                            <p class="blue">Isi dari file excel harus tanpa header</p>
                            <p class="blue">Urutan : 3 Digit Kode Cabang, NIK, Nama, Suhu / Temperatur, Tanggal</p>
                            <p class="blue"><b><u><i>Format tanggal yyyy-mm-dd</i></u></b></p>
                            <p class="red"><b><u><i>Contoh file template : <a href="<?=url('file/contoh template.xlsx');?>">Download</a></i></u></b></p>
                        </label>
                        <div class="col-sm-6 col-12">
                            <input type="file" name="upload_file" required="required" class="form-control" autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-sm-3 offset-sm-7">
                            <button type="submit" class="btn btn-primary btn-block">Upload </button>
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