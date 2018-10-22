<?php
if(isset($errors) && !empty($errors) && count($errors) >= 1)
{
	foreach($errors as $title_msg => $msg)
		echo '<div class="msg error"><b>'.$title_msg.'</b><br>'.$msg.'</div>';
}

if(isset($success) && !empty($success) && count($success) >= 1)
{
	foreach($success as $title_msg => $msg)
		echo '<div class="msg success"><b>'.$title_msg.'</b><br>'.$msg.'</div>';
}
?>
