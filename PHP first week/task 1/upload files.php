<?php

$template = showmenu();

if(isset($_POST["SendFile"]))
	uploadfile($template);
elseif(isset($_POST["ShowFile"]))
	showfiles($template, $_POST["ShowFile"]=="Показать загруженные файлы");
elseif(isset($_POST["DeleleFile"]))
	deletefile($template);
	
function showmenu(){
	$template = file_get_contents('index.tpl'); // получаем шаблон в переменную
	if(!isset($_POST["SendFile"])&&
	   !isset($_POST["ShowFile"])&&
	   !isset($_POST["DeleleFile"])){
	    $template = str_replace("{НАДПИСЬ_НА_КНОПКЕ}", "Показать загруженные файлы", $template);
		echo str_replace("{FILES_FOR_SHOW}", "", $template);
	}else
		return $template;
}

function showfiles($template, $show = true){
	if($show){
		if($dir = opendir('./files') ){
			$str_files = [];
			$str_files[] = "<br>Файлы:<br><br>";
			$i = 0;
			while(false !== ($file = readdir($dir))){
				if($file == '..' || $file == '.') continue;
				$str_files[] = 
				"<form name='form_$i' action='upload files.php' method='POST'>
					<input type='submit' name='DeleleFile' value='x' />$file
					<input type='hidden' name='filename' value='$file' />
				</form>";
				$i++;
			}
			
			closedir($dir);
			$template = str_replace("{НАДПИСЬ_НА_КНОПКЕ}", "Скрыть список файлов", $template);
			echo str_replace("{FILES_FOR_SHOW}", implode(" " , $str_files), $template);
		}
	}else{
		$template = str_replace("{НАДПИСЬ_НА_КНОПКЕ}", "Показать загруженные файлы", $template);
		echo str_replace("{FILES_FOR_SHOW}", "", $template);
	}
}

function uploadfile($template){
	$file = $_FILES['userfile'];
	$uploadfile = './files/' . basename($file['name']);
	
	if( move_uploaded_file($file['tmp_name'],  $uploadfile) )
		showfiles($template);
	else
		echo "Произошла ошибка!!!";
}

function deletefile($template){
	unlink('./files/' . $_POST['filename']);
	showfiles($template);
}

?>