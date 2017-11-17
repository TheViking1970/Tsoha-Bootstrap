<?php

	class User extends BaseModel{
		public $id, $name, $password, $motto, $datum; 	
	  	
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT * FROM Users"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$returnArray[] = new User(array(
					'id'	=> $row['id'],
					'name'	=> $row['name'],
					'datum'	=> $row['datum']
				));
			}
			return $returnArray;
		}

		public static function find($id){
			$query = DB::connection()->prepare(
				"SELECT * FROM Users WHERE id=:id LIMIT 1"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$returnArray[] = new User(array(
					'id'		=> $row['id'],
					'name'		=> $row['name'],
					'password'	=> $row['password'],
					'motto'		=> $row['motto'],
					'datum'		=> $row['datum']
				));
			}
			return $returnArray;
		}
		
		public static function save($attrArray){
			$query = DB::connection()->prepare(
				"INSERT INTO Users (name, password, motto, datum) 
				VALUES (:name, :password, :motto, :datum)"
			);
			$query->execute($attrArray);
		}

		public static function getAdderofComputer($id){
			$query = DB::connection()->prepare(
				"SELECT Users.id AS id, Users.name AS name, Logs.datum AS datum FROM Users, Logs WHERE Logs.comp_id=:id AND Users.id=Logs.user_id ORDER BY Logs.id ASC LIMIT 1"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$return = new User(array(
					'id'		=> $row['id'],
					'name'		=> $row['name'],
					'datum'		=> $row['datum']
				));
			}
			return $return;
		}

		public static function test(){
			return 'Hello World!';
		}

	}
