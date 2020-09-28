<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Setting';
	$breadcrumb[1]['url'] = url('backend/setting');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Setting')

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left" style="width : 100%">
			<h3>Setting</h3>
		</div>
	</div>
	<div class="clearfix"></div>
	@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                    @include('backend.elements.notification')
                    {{ Form::open(['url' => 'backend/setting', 'method' => 'POST','class' => 'form-horizontal', 'files' => true]) }}
                    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="item form-group">
                                <label class="control-label col-sm-3 col-xs-12">Website Title</label>
                                <div class="col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="1" placeholder="Title" autocomplete="off" value="<?=getData('web_title')?>" />
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-sm-3 col-xs-12">Website Logo <br/><small>Max file size : 1Mb</small></label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="file" name="logo" class="dropify" data-default-file="<?=url(getData('logo'))?>"/>
                                    <input type="hidden" name="default_logo" value=<?=url(getData('logo'))?>>
                                </div>
                            </div>
                                        
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-9 offset-sm-3">
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
    <link href="<?=url('vendors/dropify/css/dropify.min.css');?>" rel="stylesheet">
@endsection

<!-- JAVASCRIPT -->
@section('script')
    <script src="<?=url('vendors/dropify/js/dropify.js');?>"></script>
    <script>
        $('.dropify').dropify();
    </script>
@endsection