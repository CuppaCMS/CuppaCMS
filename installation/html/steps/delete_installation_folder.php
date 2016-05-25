<?php
	function delete_directory($dirname) {
		if (is_dir($dirname)) $dir_handle = opendir($dirname);
		if (!$dir_handle) return false;
		while($file = readdir($dir_handle)) { 
			if ($file != "." && $file != "..") {
				if (!is_dir($dirname."/".$file)) 
					unlink($dirname."/".$file);
				else
					delete_directory($dirname.'/'.$file);
			}
		}
		closedir($dir_handle);
		rmdir($dirname);
		return true;
	}
	$result = delete_directory('../installation');
	if($result){
		echo "<META HTTP-EQUIV='refresh' CONTENT='3; URL=../'>";
	}
?>
<div class="table_no_info">
    <div class="no_file" style="text-align: center; padding: 40px 0px;">
        <img src="../templates/default/images/template/face.png" style="vertical-align: middle;"  />
        <div style="display: inline-block; text-align: left; margin-left: 10px; vertical-align: middle;">
            <h2 style="color: #777;"><?php echo (@$result) ? "Great! " : "Error"; ?></h2>
            <div style="max-width: 250px; color: #AAA; line-height: 20px;">
                <?php if(@$result){ ?>
                    The folder was deleted correctly
                <?php }else{ ?>
                    The folder <span class="highlight_blue">installation</span> was not deleted, please delete it manually and <a style="text-decoration: underline; color: #1a6deb;" href="../">click here</a> go to login panel
                <?php } ?>
            </div>
        </div>
    </div>
</div>