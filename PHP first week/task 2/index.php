<?php

	include "Work with file.php";
	$a = new workWithFile('a.txt');
	$template = file_get_contents("template/index.php");
	$string_template = "";
	
	function show_result($template, $topic, $res = []){
		$template_ = $template;
		$template_ = str_replace("%topic%", $topic, $template_);
		$string_result = "";
		foreach($res as $key => $val){
			if(is_string($key))
				$string_result .= "$key: $val<br>";
			else
				$string_result .= "$val<br>";
		}
		return str_replace("%result%", $string_result, $template_);
	}
	
	$arr = [];
	$arr["string 2"] = $a->get_file_string(2);
	$arr["string 1"] = $a->get_file_string(1);
	$arr["string 19"] = $a->get_file_string(19);
	$string_template .= show_result($template, "Get string from file", $arr);
	
	$arr = [];
	$a->replace_string(19, "88");
	$string_template .= show_result($template, "File content after replase 19 string on '88' [\$a->replace_string(19, '88')]: ", [$a->get_file_content()]);
	
	$arr = [];
	for($i = 1; $i < 30; $i++){
		$arr[] = $a->get_char_string($i);
	}
	$string_template .= show_result($template, "File content at char: ", $arr);
	
	$arr = [];
	$a->replace_char(19, 1, '5');
	$arr[] = $a->get_file_content();
	$string_template .= show_result($template, "File content after replace 1st char at 19 string [\$a->replace_char(19, 1, '5')]: ", $arr);
	
	echo $string_template;

?>