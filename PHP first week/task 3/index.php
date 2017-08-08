<?php

	class SQL{
		public function get_select_query($table, $fields, $where, $order_by){
			$query = "SELECT ";
			if(count($fields) > 0){
				$arr = [];
				foreach($fields as $field => $value)
					$arr[] = $value;
				$query .= implode(", ", $arr);
			}else{
				$query .= "*";
			}
			
			$query .= " FROM $table ";
			
			$arr = [];
			if(count($where) > 0){
				$query .= " WHERE ";
				foreach($where as $field => $value)
					$arr[] = "$field=$value";
				$query .= implode(" and ", $arr);
			}
			
			if(count($order_by) > 0){
				$arr = [];
				foreach($order_by as $field => $value)
					$arr[] = $value;
				$query .= " ORDER BY " . implode(", ", $arr);
			}
			
			return $query;
			
		}
		
		public function get_insert_query($table, $fields){
			//Передаётся
			//1. $table - Имя таблицы
			//2. $fields - массив ["имя_поля1" => "значение", "имя_поля2" => "значение", "имя_поля3" => "значение"]
			
			$query = "INSERT INTO $table ";
			$flds = [];
			$vles = [];
			foreach($fields as $field => $value){
				$flds[] = $field;
				$vles[] = "'$value'";
			}
			
			$query .= "(" . implode(", ", $flds) . ")";
			$query .= " VALUES ";
			$query .= "(" . implode(", ", $vles) . ")";
			
			return $query;
		}
		
		public function get_delete_query($table, $where){
			//Передаётся
			//1. $table - Имя таблицы
			//2. $where - массив ["имя_поля1" => "значение_сравнения"]
			
			$query = "DELETE FROM $table ";

			$arr = [];
			if(count($where) > 0){
				$query .= " WHERE ";
				foreach($where as $field => $value)
					$arr[] = "$field=$value";
				$query .= implode(" and ", $arr);
			}
			
			return $query;
		}
		
		public function get_update_query($table, $fields, $where){
			//Передаётся
			//1. $table - Имя таблицы
			//2. $fields - массив ["имя_поля1" => "значение", "имя_поля2" => "значение", "имя_поля3" => "значение"]
			//3. $where - массив ["имя_поля1" => "значение_сравнения"]
			
			$query = "UPDATE $table SET ";
			$arr = [];
			foreach($fields as $field => $value)
				$arr[] = "$field='$value'";
			$query .= implode(", ", $arr);

			$arr = [];
			if(count($where) > 0){
				$query .= " WHERE ";
				foreach($where as $field => $value)
					$arr[] = "$field=$value";
				$query .= implode(" and ", $arr);
			}
			
			return $query;
		}
		
		public function parent_select($database, $args){
			$table = $args["table"]===undefined?[]:$args["table"];
			$fields = $args["fields"]===undefined?[]:$args["fields"];
			$where = $args["where"]===undefined?[]:$args["where"];
			$order_by = $args["order_by"]===undefined?[]:$args["order_by"];
			$query = $this->get_select_query($table, $fields, $where, $order_by);
			return $query; //для отладки
			if($database == "mysql"){
				$res_array = [];
				if ($result = $args["sqlBase"]->query( $query ))
					while ($row = $result->fetch_assoc() )
						$res_array[] = $row;
				return $res_array;
			}elseif($database == "mssql"){
				return $args["sqlBase"]->query( $query );
			}elseif($database == "pgsql"){
				return $args["sqlBase"]->query( $query );
			}
		}
		
		public function parent_insert($database, $args){
			$query = $this->get_insert_query($args["table"], $args["fields"]);
			return $query; //для отладки
			if($database == "mysql"){
				return $args["sqlBase"]->query( $query );
			}elseif($database == "mssql"){
				return $args["sqlBase"]->exec( $query );
			}elseif($database == "pgsql"){
				return $args["sqlBase"]->exec( $query );
			}

		}
		
		public function parent_delete($database, $args){
			$query = $this->get_delete_query($args["table"], $args["where"]);
			return $query; //для отладки
			if($database == "mysql"){
				return $args["sqlBase"]->query( $query );
			}elseif($database == "mssql"){
				return $args["sqlBase"]->exec( $query );
			}elseif($database == "pgsql"){
				return $args["sqlBase"]->exec( $query );
			}
		}
		
		public function parent_update($database, $args){
			$query = $this->get_update_query($args["table"], $args["fields"], $args["where"]);
			return $query; //для отладки
			if($database == "mysql"){
				return $args["sqlBase"]->query( $query );
			}elseif($database == "mssql"){
				return $args["sqlBase"]->exec( $query );
			}elseif($database == "pgsql"){
				return $args["sqlBase"]->exec( $query );
			}
		}

		public function __call($funcname, $args = []){
			if( $funcname == "select"||
				$funcname == "delete"||
				$funcname == "insert"||
				$funcname == "update"){
					$func = "parent_" . $funcname;
					return $this->$func($args[0]["database"], $args[0]["query_params"]);
			}else
				echo "Передана неизвестная функция";
		}
	}
	
	class MYSQL extends SQL{
		private $sqlBase; //подключение к БД
		
		public function __construct(...$params){ //конструктор
			/*	в конструкторе используется только первых четыре параметра:
				1 - host
				2 - username
				3 - password
				4 - dbname
			*/
			
			$parameters = [	"host" => "localhost",
							"username" => "root",
							"password" => "",
							"dbname" => "bookstore"];
			
			if(count($params) > 0){
				$i = 0;
				foreach($this->parameters as $key => $value){
					if ($i >= min(count($params), 4))break;
					$parameters[$key] = $params[$i];
					$i++;
				}
			}
			
			$this->sqlBase = new mysqli(
						$parameters["host"],
						$parameters["username"], 
						$parameters["password"], 
						$parameters["dbname"]
								);

			if ($this->sqlBase->connect_errno) {
				die("Something went wrong while connecting to MYSQL");
			};

			if (!$this->sqlBase->set_charset("utf8")) {
				$msg_err = "Ошибка при загрузке набора символов utf8: " . $sqlBase->error;
			}
		}
		
		public function __destruct(){
			mysqli_close($this->sqlBase);
		}
		
		public function select($table, $fields, $where, $order_by){
			
			return parent::select([ "database" => "mysql", 
									"query_params" => ["sqlBase" => $this->sqlBase, 
													   "table" => $table, 
													   "fields" => $fields,
													   "where" => $where, 
													   "order_by" => $order_by]
								]);
			
			/*$query = parent::get_select_query($table, $fields);
			return $query;//для отладки
			
			$res_array = [];
			if ($result = $this->sqlBase->query( $query ))
				while ($row = $result->fetch_assoc() )
					$res_array[] = $row;
				
			return $res_array;*/
		}
		
		public function insert($table, $fields){
			return parent::insert([ "database" => "mysql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "fields" => $fields]]);
			
			/*$query = parent::get_insert_query($table, $fields);
			return $query; //для отладки
			return $this->sqlBase->query($query);*/
		}
		
		public function delete($table, $where){
			return parent::delete([ "database" => "mysql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "where" => $where]]);

			/*$query = $this->get_delete_query($table, $where);
			return $query; //для отладки
			return $this->sqlBase->query($query);*/
		}
		
		public function update($table, $fields, $where = []){
			return parent::update([ "database" => "mysql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "fields" => $fields,
											   "where" => $where]]);

			/*$query = $this->get_update_query($table, $fields, $where);
			return $query; //для отладки
			return $this->sqlBase->query($query);*/
		}
		
	}
	
	class MSSQL extends SQL{
		private $sqlBase; //подключение к базе данных
		
		public function __construct(...$params){ //конструктор
			/*	в конструкторе используется только первых пять параметров:
				1 - host
				2 - port
				3 - username
				4 - password
				5 - dbname
			*/
			
			$parameters = [	"host" => "localhost",
							"port" => 80,
							"username" => "root",
							"password" => "",
							"dbname" => "bookstore"];
			
			if(count($params) > 0){
				$i = 0;
				foreach($this->parameters as $key => $value){
					if ($i >= min(count($params), 5))break;
					$parameters[$key] = $params[$i];
					$i++;
				}
			}
			
			try{
				$this->sqlBase = new PDO('mssql:host=' . $parameters["host"] . ',' . $parameters["port"] . ';dbname=' . $parameters["dbname"] . ';charset=utf8', 
									 $parameters["username"], 
									 $parameters["password"]);
			}catch (PDOException $e){
				$e->getMessage();
			}
			
		}

		public function __destruct(){
			$this->sqlBase = null;
		}

		public function select($table, $fields = [], $where = [], $order_by = []){
			return parent::select([ "database" => "mssql", 
									"query_params" => ["sqlBase" => $this->sqlBase, 
													   "table" => $table, 
													   "fields" => $fields,
													   "where" => $where, 
													   "order_by" => $order_by]
								]);

			/*$query = $this->get_select_query($table, $fields, $where, $order_by);
			return $query;//для отладки
			return $this->query($query);*/
		}
	
		public function insert($table, $fields){
			return parent::insert([ "database" => "mssql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "fields" => $fields]]);

			/*$query = $this->get_insert_query($table, $fields);
			return $query; //для отладки
			return $this->exec($query);*/
		}
		
		public function delete($table, $where){
			return parent::delete([ "database" => "mssql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "where" => $where]]);

			/*$query = $this->get_delete_query($table, $where);
			return $query; //для отладки
			return $this->exec($query);*/
		}
		
		public function update($table, $fields, $where = []){
			return parent::update([ "database" => "mssql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "fields" => $fields,
											   "where" => $where]]);
											   
			/*$query = $this->get_update_query($table, $fields, $where);
			return $query; //для отладки
			return $this->exec($query);*/
		}
		
	}
	
	class PostgreSQL extends SQL{
		private $sqlBase; //подключение к базе данных
		
		public function __construct(...$params){ //конструктор
			/*	в конструкторе используется только пять параметров:
				1 - host
				2 - port
				3 - username
				4 - password
				5 - dbname
			*/
			
			$host = "localhost";
			$port = 80;
			$username = "root";
			$password = "";
			$dbname = "bookstore";
			
			if(count($params) > 0){
				switch (count($params)){
					case 5: $dbname = $params[4];
					case 4: $password = $params[3];
					case 3: $username = $params[2];
					case 2: $port = $params[1];
					case 1: $host = $params[0];
				}
			}
			
			
			try{
				$this->sqlBase = new PDO("pgsql:host=$host,port=$port;dbname=$dbname;charset=utf8", 
									 $username, $password);
			}catch (PDOException $e){
				$e->getMessage();
			}
			/*
			$this->sqlBase = PQconnectdb("host=$host 
										port=$port 
										dbname=$dbname
										user=$username
										password=$password
										options='--client_encoding=UTF8'");*/
			
			
			
		}

		public function __destruct(){
			$this->sqlBase = null;
		}

		public function select($table, $fields = [], $where = [], $order_by = []){
			return parent::select([ "database" => "pgsql", 
						"query_params" => ["sqlBase" => $this->sqlBase, 
										   "table" => $table, 
										   "fields" => $fields,
										   "where" => $where, 
										   "order_by" => $order_by]
					]);

			/*$query = $this->get_select_query($table, $fields, $where, $order_by);
			return $query;//для отладки
			$result = $this->exec($this->sqlBase, $query);
			if(!$result) return [];
			return pg_fetch_row($result);*/
		}
	
		public function insert($table, $fields){
			return parent::insert([ "database" => "pgsql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "fields" => $fields]]);

			/*$query = $this->get_insert_query($table, $fields);
			return $query; //для отладки
			return $this->exec($this->sqlBase, $query);*/
		}
		
		public function delete($table, $where){
			return parent::delete([ "database" => "pgsql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "where" => $where]]);
			/*$query = $this->get_delete_query($table, $where);
			return $query; //для отладки
			return $this->exec($this->sqlBase, $query);	*/	
		}
		
		public function update($table, $fields, $where = []){
			return parent::update([ "database" => "pgsql",
									"query_params" => ["sqlBase" => $this->sqlBase,
											   "table" => $table, 
											   "fields" => $fields,
											   "where" => $where]]);
											   
			/*$query = $this->get_update_query($table, $fields, $where);
			return $query; //для отладки
			return $this->exec($this->sqlBase, $query);	*/	
		}
		
	}
	
	$a = new MYSQL();
	echo $a->select("genre", [], ["genre_name" => "New Genre"]) . "<br><br>";
	//echo $a->insert("books", ["book_id" => 3]) . "<br><br>";
	
	/*$b = new MSSQL();
	echo $b->select("genre", [], ["genre_name" => "New Genre"]) . "<br><br>";

	$c = new PostgreSQL();
	echo $c->delete("author", ["author_name" => "New Author"]) . "<br><br>";*/
?>