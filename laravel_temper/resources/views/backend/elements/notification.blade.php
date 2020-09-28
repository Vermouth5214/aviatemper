@if (Session::has('success'))
    <div class="row">
        <div class="alert alert-<?=Session::get('mode');?> alert-dismissible fade show w-100" role="alert">
		    {{ Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        </div>
    </div>
@endif