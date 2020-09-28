<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Dashboard')

<!-- CONTENT -->
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Dashboard</h3>
        </div>
        <div class="title_right">
        </div>
    </div>
    <div class="clearfix"></div>


@endsection

<!-- CSS -->
@section('css')

@endsection

<!-- JAVASCRIPT -->
@section('script')

@endsection