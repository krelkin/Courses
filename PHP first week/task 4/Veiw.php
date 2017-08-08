<?php

class Display{
	
	public static function get_errors($array_errors){
		$errors = [];
		
		foreach($array_errors as $key => $value){
			if($value == false){
				if($key == "name")
					$errors[] = "Не заполнено имя";
				elseif($key == "email")
					$errors[] = "Не заполнен e-mail";
				elseif($key == "message")
					$errors[] = "Не заполнено обращение";
			}
		}
		return $errors;
	}
	
	public static function get_result(){
		return mail($_POST["email"], "Сообщение от " . $_POST['name'], $_POST["message"]);
	}
	
	public static function show_template($errors, $result, $template){
		$template = str_replace("%errors%", implode(",<br>", $errors), $template);
		return str_replace("%result%", $result, $template);
	}
}
    
?>