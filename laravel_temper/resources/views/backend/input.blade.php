<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/input');
	$breadcrumb[1]['title'] = 'Input Data';
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title')
	Input Data
@endsection

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left">
			<h3>Input Data</h3>
		</div>
		<div class="title_right">

        </div>
        <div class="clearfix"></div>
		@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	</div>
	<div class="clearfix"></div>
	<br/><br/>	
	<div class="row">
		<div class="col-12">
			<div class="x_panel">
				<div class="x_content">
					<div class="error-alert"></div>
					{{ Form::open(['class' => 'form-horizontal form-label-left', 'id' => 'formInput']) }}
						{!! csrf_field() !!}
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Search by</label>
							<div class="col-sm-6">
								<div class="form-check-inline">
									<label class="form-check-label">
										<?php
											$checked = '';
											$readonly = "disabled";
											if ($mode == "PUSAT"){
												$checked = "checked";
												$readonly = "";
											}
										?>
								  		<input type="radio" class="form-check-input" name="mode" <?=$checked;?> <?=$readonly;?> value="card"> Card Number
									</label>
							  	</div>
								<div class="form-check-inline">
									<label class="form-check-label">
										<?php
											$checked = '';
											$readonly = "";
											if ($mode == "CABANG"){
												$checked = "checked";
												$readonly = "disabled";
											}
										?>
								  		<input type="radio" class="form-check-input" name="mode" <?=$checked;?> <?=$readonly;?> value="name"> Name
									</label>
							  	</div>
							</div>
						</div>
						<div id ="search-by-number">
							<div class="form-group row">
								<label class="col-form-label col-sm-2">Search</label>
								<div class="col-sm-6 col-8 col-lg-4">
									<input type="text" name="search_number" id="search-number" class="form-control">
									<input type="hidden" name="search_number_real" id="search-number-real" class="form-control">
								</div>
								<div class="col-sm-4 col-4 col-lg-2">
									<button class="btn btn-info btn-block" id='btn-clear'>Clear</button>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-sm-2">Name</label>
								<div class="col-sm-6 col-8 name" id="name">
									
								</div>
							</div>
						</div>
						<div class="form-group row" id='search-by-name'>
							<label class="col-form-label col-sm-2">Search</label>
							<div class="col-sm-8 col-lg-4">
								{{
								Form::select(
									'search_name',
									$data,
									'',
									array(
										'class' => 'form-control',
										'id' => 'search-name'
									))
								}}
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Temperature</label>
							<div class="col-sm-4 col-5 col-lg-3">
								<input type="tel" name="temperature" id="temperature" class="form-control">
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-sm-12 col-lg-4 offset-lg-2">
								<button type="submit" class="btn btn-primary btn-block btn-submit">Submit </button>
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
	<style>
		.btn-submit{
			height : 80px;
		}
		@media (min-width: 992px) { 
			.btn-submit{
				height : 40px;
			}
		}
	</style>	
@endsection

<!-- JAVASCRIPT -->
@section('script')
    <!-- jquery.inputmask -->
    <script src="<?=url('vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js');?>"></script>

    <!-- select2 -->
	<script src="<?=url('vendors/select2/dist/js/select2.min.js');?>"></script>
	<script>
		$('#search-name').select2({
			closeOnSelect: true
		});

        $('#search-name').on('select2:close', function () {
			$('#temperature').focus();
        })		

		$('#btn-clear').on('click', function(e){
			$('#search-number').val('').focus();
			$('#search-number-real').val('');
			$("#name").html('');
			return false;
		})

		$('#search-number').on('keypress', function(e){
			if(e.which == 13) {
     		   //ajax
				var number = $('#search-number').val();
				$('#search-number-real').val(number);
				var url = "<?php echo url('/'); ?>/backend/input/search/"+number;
				$.ajax({
					type: "GET",
					url: url,
					success: function(response){ 
						if(typeof response=="object")
						{
							if (response.status){
								$("#name").html("<h5>"+response.name+"</h5>");
							} else {
								$("#name").html('<h5>Not Found</h5>');
							}
							$('#temperature').focus();
						}
						else
						{
							alert('Session expired. Please relogin');
							window.location.reload(true);
						}						
					}, 
					error: function(response){
						console.log(response);
					}
				});
				return false;
			}
		})

		$('#temperature').on('keypress', function(e){
			if(e.which == 13) {
				return false;
			}
		})

		function show(){
			$('#search-by-name').addClass('d-none');
			$('#search-by-number').addClass('d-none');
			$('#search-number').removeAttr("required");
			$('#search-name').removeAttr("required");
			var mode = $('input[name="mode"]:checked').val();
			if (mode == "card"){
				$('#search-by-name').addClass('d-none');
				$('#search-by-number').removeClass('d-none');
				$('#search-number').attr("required", "true");
				$('#search-number').focus();
			}
			if (mode == "name"){
				$('#search-by-name').removeClass('d-none');
				$('#search-by-number').addClass('d-none');
				$('#search-name').attr("required", "true");
				$('#search-name').focus();
			}
		}
		show();

		$('input[type=radio][name="mode"]').change(function() {
			show();
		});

		$('#temperature').inputmask({
            mask: '39.9',
			placeholder: '0',
        });

		$('.btn-submit').on('click', function(){
			$('.error-alert').html("");
			$('.btn-submit').attr('disabled', true);
			var temperature = $('#temperature').val();
			if (temperature == ''){
				alert('Temperature must be filled');
				$('#temperature').focus();
				return false;
			}

			//ajax submit
			var url = "<?php echo url('/'); ?>/backend/input";
			var frm_data = $("#formInput").serialize();
			$.ajax({
				type: "POST",
				url: url,
				timeout: 2500,
				data: frm_data,
				success: function(response){ 
					if (response.status) {
						$('.error-alert').html("");
						$('.error-alert').append('<div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert">&times;</button>Data saved successfully</div>');

						//clear data
						$('#search-number').val('').focus();
						$('#search-number-real').val('');
						$("#name").html('');
						$('#search-name').val('');
						$('#temperature').val('');
						
						var mode = $('input[name="mode"]:checked').val();
						if (mode == "name"){
							$('#search-name').select2({
								closeOnSelect: true
							});
						}
					}
					else {
						$('.error-alert').html("");
						$('.error-alert').append('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert">&times;</button>Data Not Found</div>');

						$('#temperature').val('');
					}
				}, 
				error: function(response){
					console.log(response);
					$('.btn-submit').removeAttr('disabled');
					alert('Submit Error. Please try again.');
					location.reload();
				}
			});
			$('.btn-submit').removeAttr('disabled');	
			return false;
		});

	</script>
@endsection