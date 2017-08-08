<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>PHP first week</title>
	</head>
	
	<body>
		<form enctype="multipart/form-data" action="upload files.php" method="POST">
			<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
			<!-- Название элемента input определяет имя в массиве $_FILES -->
			Отправить этот файл: <input name="userfile" type="file" /> <br>
			<input type="submit" name = "SendFile" value="Send File" />
			<br><br>
			<input type="submit" name = "ShowFile" value="{НАДПИСЬ_НА_КНОПКЕ}" />
			
		</form>
		
		{FILES_FOR_SHOW}
		
	</body>

</html>