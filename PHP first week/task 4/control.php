<?php
	
	function check_user_data(){
		$check_name = false;
		$check_email = false;
		$check_message = false;
		if(isset($_POST["name"]) && trim($_POST["name"]) !== '') $check_name = true;
		if(isset($_POST["email"]) && trim($_POST["email"]) !== '') $check_email = true;
		if(isset($_POST["message"]) && trim($_POST["message"]) !== '') $check_message = true;
		if($check_message && $check_email && $check_name)
			return [];
		return Display::get_errors(["name" => $check_name, "email" => $check_email, "message" => $check_message]);
	}
	
	function get_result($errors){
		if(count($errors) > 0) 
			return "";
		if(Display::get_result())
			return "Сообщение отправлено";
	}
	
	function show_template($errors, $result, $file_template){
		return Display::show_template($errors, $result, $file_template);
	}
	
?>