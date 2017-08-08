<?php
	
	class workWithFile{
		private $filename;
		private $file_array;
		
		function __construct($filename){
			$this->set_filename($filename);
		}
		
		function __destruct(){
			
		}
		
		public function set_filename($filename){
			$this->filename = $filename;
			$this->file_array = file($filename);
		}
		
		public function get_file_content(){
			return implode("<br>", $this->file_array);
		}
		
		public function get_file_string($index = 0){//Построчный доступ к данным (нумерация начинаются с 1)
			if($index - 1 < count($this->file_array) )
				return $this->file_array[$index - 1];
			else
				return false;
		}
		
		public function get_char_string($index){//Посимвольный доступ к данным (нумерация начинаются с 1)
			$array_ind = 0;
			foreach($this->file_array as $k => $str){
				if($array_ind + (strlen(trim($str))) >= $index) break;
				$array_ind += (strlen(trim($str)));
			}
			return $str[$index - 1 - $array_ind];
		}
		
		public function replace_string($index, $str){//Заменить строку целиком (нумерация начинаются с 1)
			$this->file_array[$index - 1] = $str;
			$this->save_file();
		}
		
		public function replace_char($str_index, $ch_index, $ch){//Заменить символ (нумерация начинаются с 1)
			$this->file_array[$str_index - 1][$ch_index - 1] = $ch;
			$this->save_file();
		}
		
		private function save_file(){//Сохранить файл
			$file = fopen($this->filename, "w");
			foreach ($this->file_array as $k => $output){
				if(!fwrite($file, $output)){
					fclose($file);
					return false;
				}
			}
			fclose($file);
			$this->set_filename($this->filename);
		}
	}
	
?>