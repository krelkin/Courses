<?php 
/*а) Создать связанную структуру из обьектов при помощи понятия Интерфейс, а именно Создать модель музыкальной группы, 
	в которую могут вступать музыканты играющие на различных инструментах.

	Интерфейсы для задания:*/
	interface iBand{
		public function getName();
		public function getGenre();
		public function addMusician(iMusician $obj);
		public function getMusician();
	}

	interface iMusician{
		public function getName(); // добавлено мной
		public function addInstrument(iInstrument $obj);
		public function getInstrument();
		public function assignToBand(iBand $nameBand);
		public function getMusicianType();
	}

	interface iInstrument{
		public function getName();
		public function getCategory();
	} 
	
	
	class cBand implements iBand{
		
		private $name;
		private $genre;
		private $musicians = array();
		
		public function getName(){
			return $this->name;
		}
		
		public function getGenre(){
			return $this->$genre;
		}
		
		public function addMusician(iMusician $musician){
			$this->musicians[] = $musician;
		}
		
		public function getMusician(){
			$arr = [];
			foreach($musicians as $musician){
				$arr[] = $musician->getName();
			}
			return implode(", ", $arr);
		}
		
	}
	
	class cMusician implements iMusician{
		private $name;
		private $instruments = array();
		private $musicianType;
		
		public function getName(){
			return $this->name();
		}
		
		public function addInstrument(iInstrument $instrument){
			$this->instruments[] = $instrument;
		}
		
		public function getInstrument(){
			$arr = [];
			foreach($instruments as $instrument){
				$arr[] = $instrument->getName();
			}
			return implode(", ", $arr);
		}
		
		public function assignToBand(iBand $Band){
			$Band->addMusician($this);
		}
		
		public function getMusicianType(){
			return $this->musicianType;
		}
	}
	
	class cInstrument implements iInstrument{
		private $name;
		private $category;
		
		public function getName(){
			return $this->name;
		}
		
		public function getCategory(){
			return $this->category;
		}
	}
	
	
	
	
	
	