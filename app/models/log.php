<?php

	class Log extends BaseModel{
		public $id, $comp_id, $user_id, $datum; 	
	  	
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT * FROM Logs"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$returnArray[] = new Log(array(
					'id'	=> $row['id'],
					'comp_id'	=> $row['comp_id'],
					'user_id'	=> $row['user_id'],
					'datum'	=> $row['datum']
				));
			}
			return $returnArray;
		}

		public static function find($id){
			$query = DB::connection()->prepare(
				"SELECT Logs.id AS id, Logs.comp_id AS comp_id, Logs.user_id AS user_id, Logs.datum AS datum, Users.name AS name FROM Logs, Users WHERE Logs.comp_id=:id AND Users.id=Logs.user_id ORDER BY Logs.id ASC"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$returnArray[] = new Log(array(
					'id'		=> $row['id'],
					'comp_id'	=> $row['comp_id'],
					'user_id'	=> $row['user_id'],
					'datum'		=> $row['datum'],
					'name'		=> $row['name']
				));
			}
			return $returnArray;
		}
		
		public static function save($attrArray){
			$query = DB::connection()->prepare(
				"INSERT INTO Logs (comp_id, user_id, datum) 
				VALUES (:comp_id, :user_id, :datum)"
			);
			$query->execute($attrArray);
		}


		public static function test(){
			return 'Hello World!';
		}

	}
