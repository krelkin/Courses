<?php

/*б) Сделать следующего реализацию интерфейса iWorkData в классах, создав классы для сессии, куков, MySQL.
	Объекты классов должны добавлять/читать/удалять данные*/
	
	interface iWorkData{
		public function saveData($key, $val);
		public function getData($key);
		public function deleteData($key);
	}	

	class Session implements iWorkData{
		private $params = array();
		
		public function saveData($key, $val){
			$this->params[$key] = $val;
		}
		
		public function getData($key){
			return $this->params[$key];
		}
		
		public function deleteData($key){
			unset($this->params[$key]);
			$params = array_diff($params, array('', NULL));
		}
	}

	class Cookie implements iWorkData{
		public function saveData($key, $val){
			setcookie($key, $val);
		}
		
		public function getData($key){
			if(isset($_COOKIE[$key]))
				return $_COOKIE[$key];
			else
				return NULL;
		}
		
		public function deleteData($key){
			if(Cookie::getData($key) !== NULL)
				setcookie($key, "", time() - 1);
		}
	}
	
	class MySQL implements iWorkData{
		private $table = "test_table";
		private $sqlBase;
		
		public function __construct(){
			$this->sqlBase = new mysqli("localhost", "root", "", "test");
			if ($this->sqlBase->connect_errno){
				die("Something went wrong while connecting to MYSQL");}
			$this->sqlBase->set_charset("utf8");
		}
		
		public function saveData($key, $val){
			if($this->getData($key) === false)
				$query = "INSERT INTO $this->table (id, value) VALUES ('$key', '$val')";
			else 
				$query = "UPDATE $this->table SET value = '$val' WHERE id = '$key'";
			$this->sqlBase->query( $query );
			
		}
		
		public function getData($key){
			$query = "SELECT value FROM $this->table WHERE id = '$key'";
			$result = $this->sqlBase->query( $query );
			$res_arr = $result->fetch_all(MYSQLI_ASSOC);
			if(count($res_arr) > 0){
				return $res_arr[0]["value"];
			}else 
				return false;
		}
		
		public function deleteData($key){
			$query = "DELETE FROM $this->table WHERE id = '$key'";
			$this->sqlBase->query( $query );
		}
	}
	


?>