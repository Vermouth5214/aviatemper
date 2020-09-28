<?php
	if (!empty($data)):
		$data = $data[0];
?>
	<div class="x_panel">
		<div class="x_content">
            <label>ID :</label>
            <span class="form-control"><?=$data->id;?></span>
    
            <label>Username :</label>
            <span class="form-control"><?=$data->username;?></span>
    
            <label>Level :</label>
            <span class="form-control"><?=$data->name;?></span>

            <label>Name :</label>
    		<span class="form-control"><?=$data->name;?></span>

            <label>Email :</label>
            <span class="form-control"><?=$data->email;?></span>

            <label>Lokasi :</label>
            <span class="form-control"><?=$lokasi->nama_lokasi;?></span>

            <label>Date Created :</label>
            <span class="form-control"><?=date('d M Y H:i:s', strtotime($data->created_at));?></span>

            <label>Last Modified :</label>
            <span class="form-control"><?=date('d M Y H:i:s', strtotime($data->updated_at));?></span>

            <label>Last Modified by :</label>
            <span class="form-control"><?=$data->user_modified;?></span>
		</div>
	</div>
<?php
	endif;
?>

