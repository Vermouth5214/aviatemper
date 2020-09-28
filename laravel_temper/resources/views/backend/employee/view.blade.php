<?php
	if (!empty($data)):
		$data = $data[0];
?>
	<div class="x_panel">
		<div class="x_content">
            <label>ID :</label>
            <span class="form-control"><?=$data->id;?></span>
    
            <label>Name :</label>
            <span class="form-control"><?=$data->nama;?></span>
    
            <label>Position :</label>
            <span class="form-control"><?=$data->posisi;?></span>

            <label>Status :</label>
    		<span class="form-control">
                <?php
                    if ($data->active == 1){
                        $text = "Active";
                        $label = "success";
                    } else 
                    if ($data->active == 2){
                        $text = "Non Active";
                        $label = "warning";
                    }                    
                ?>
                <span class='badge badge-<?=$label;?>'><?=$text;?></span>
            </span>

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

