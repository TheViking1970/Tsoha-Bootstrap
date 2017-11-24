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
				$user = new User(array(
					'id'		=> $row['id'],
					'name'		=> $row['name'],
					'password'	=> $row['password'],
					'motto'		=> $row['motto'],
					'datum'		=> $row['datum']
				));
			}
			return $user;
		}
		
		public static function save($attrArray){
			$query = DB::connection()->prepare(
				"INSERT INTO Users (name, password, motto, datum) 
				VALUES (:name, :password, :motto, :datum)"
			);
			$query->execute($attrArray);
		}

		public static function login($username, $password) {
			$id=0;
			$query = DB::connection()->prepare(
				"SELECT * FROM Users WHERE LOWER(name)=LOWER(:name) AND password=:password LIMIT 1"
			);
			$query->execute(array('name' => $username, 'password' => $password));
			$rows = $query->fetchAll();
			foreach($rows as $row) {
				if($row['id']) {
					$id = $row['id'];
				}
			}
			if($id) {$_SESSION['userId'] = $id;}
			return $id?0:1; // if no id ==> ERROR=1 !!
		}


		public static function getAdderofComputer($id){
			$query = DB::connection()->prepare(
				"SELECT Users.id AS id, Users.name AS name, Logs.datum AS datum FROM Users, Logs WHERE Logs.comp_id=:id AND Users.id=Logs.user_id ORDER BY Logs.id ASC LIMIT 1"
			);
			$query->execute(array('id' => $id));
			$row = $query->fetch();
			if($row) {
				$return = new User(array(
					'id'		=> $row['id'],
					'name'		=> $row['name'],
					'datum'		=> $row['datum']
				));
			} else {
				$return = new User(array());
			}
			return $return;
		}

		public static function test(){
			return 'Hello World!';
		}

	}
