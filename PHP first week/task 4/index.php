<?php

	include "Veiw.php";
	include "control.php";

	$template = file_get_contents("template/index.php");
	
	if(count($_POST) > 0){
		$errors = check_user_data();
		$result = get_result($errors);
		$template = show_template($errors, $result, $template);
	}else{
		$template = show_template([], "", $template);
	}
	
	echo $template;
	
?>