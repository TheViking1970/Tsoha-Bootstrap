<?php

	class Review extends BaseModel{
		public $id, $user_id, $comp_id, $review, $rating, $datum; 	
	  	
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT * FROM Reviews"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$returnArray[] = new Review(array(
					'id'		=> $row['id'],
					'user_id'	=> $row['user_id'],
					'comp_id'	=> $row['comp_id'],
					'review'	=> $row['review'],
					'rating'	=> $row['rating'],
					'datum'		=> $row['datum']
				));
			}
			return $returnArray;
		}

		public static function find($id){
			$query = DB::connection()->prepare(
				"SELECT Reviews.id, Reviews.user_id, Reviews.review, Reviews.rating, Reviews.datum, Users.name FROM Reviews, Users WHERE comp_id=:id AND Users.id=user_id"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$returnArray[] = new Review(array(
					'id'		=> $row['id'],
					'user_id'	=> $row['user_id'],
					'review'	=> $row['review'],
					'rating'	=> $row['rating'],
					'datum'		=> $row['datum'],
					'username'	=> $row['name']
				));
			}
			return $returnArray;
		}
		
		public static function save($attrArray){
			$query = DB::connection()->prepare(
				"INSERT (user_id, comp_id, review, rating, datum) 
				VALUES (:user_id, :comp_id, :review, :rating, :datum) 
				INTO Reviews"
			);
			$query->execute($attrArray);
		}


		public static function test(){
			return 'Hello World!';
		}

	}
