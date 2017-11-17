<?php

	class Computer extends BaseModel{
		public $id, $brand, $name, $imgurl, $infotext; 	
	  	
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT * FROM Computers INNER JOIN Logs ON Computers.id=Logs.comp_id"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$returnArray[] = new Computer(array(
					'id'		=> $row['id'],
					'brand'		=> $row['brand'],
					'name'		=> $row['name'],
					'imgrul'	=> $row['imgurl'],
					'infotext'	=> $row['infotext']
				));
			}
			return $returnArray;
		}

		public static function find($id){
			$query = DB::connection()->prepare(
				"SELECT * FROM Computers WHERE id=:id LIMIT 1"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$return = new Computer(array(
					'id'		=> $row['id'],
					'brand'		=> $row['brand'],
					'name'		=> $row['name'],
					'imgurl'	=> $row['imgurl'],
					'infotext'	=> $row['infotext']
				));
			}
			return $return;
		}
		
		public function save($attrArray){
			$query = DB::connection()->prepare(
				"INSERT INTO Computers (brand, name, imgurl, infotext) 
				VALUES (:brand, :name, :imgurl, :infotext) 
				RETURNING id"
			);
			$query->execute($attrArray);
			$row = $query->fetch();
			return $row;
		}


		public static function test(){
			return 'Hello World!';
		}

	}
