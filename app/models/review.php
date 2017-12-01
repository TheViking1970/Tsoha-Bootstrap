<?php

	class Review extends BaseModel{
		/*
		 *	id = arvostelun tietokantatietueen id
		 *	user_id = arvostelijan id
		 *	comp_id = arvostellun tietokoneen id
		 *	review 	= arvosteluteksti
		 *	rating 	= arvosteluarvo [1,5]
		 *	datum 	= arvostulupäivämäärä
		 */
		public $id, $user_id, $comp_id, $review, $rating, $datum;
		/*
		 *	Tilapäisiä muuttujia
		 *	user_name 	= arvostelijan nimi
		 *	comp_brand 	= arvostellun tietokoneen merkki
		 *	comp_name 	= arvostellun tietokoneen nimi/malli
		 */
		public $user_name, $comp_brand, $comp_name;
	  	
	  	/*
	  	 *	Objektin konstruktori
	  	 */
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

	  	/*
	  	 *	Haetaan kaikki arvostelut tietokannasta
	  	 *	Tätä ei käytetä tällä hetkellä, mutta jos järjestelmään luodaan ylläpitätyökaluja niin tarvitaan
	  	 *	Return: NULL jos ei arvosteluja TAI kokoelma arvostelu-objekteja
	  	 */
		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT * FROM Reviews"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$reviews = null;
			foreach($rows as $row) {
				$reviews[] = new Review(array(
					'id'		=> $row['id'],
					'user_id'	=> $row['user_id'],
					'comp_id'	=> $row['comp_id'],
					'review'	=> $row['review'],
					'rating'	=> $row['rating'],
					'datum'		=> formatDatum($row['datum'])
				));
			}
			return $reviews;
		}

		/*
		 *	Hae tiettyyn tietokoneeseen liittyvät arvostelut
		 *	Return: NULL = ei löytynyt arvosteluja TAI kokoelma arvostelu-objekteja
		 */
		public static function findByComputer($id){
			$query = DB::connection()->prepare(
				"SELECT R.id, R.user_id, R.review, R.rating, R.datum, U.name FROM Reviews AS R, Users AS U WHERE R.comp_id=:id AND U.id=R.user_id"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$reviews = null;
			foreach($rows as $row) {
				$reviews[] = new Review(array(
					'id'		=> $row['id'],
					'user_id'	=> $row['user_id'],
					'review'	=> $row['review'],
					'rating'	=> $row['rating'],
					'datum'		=> self::formatDatum($row['datum']),
					'user_name'	=> $row['name']
				));
			}
			return $reviews;
		}
		
		/*
		 *	Hae tietyn käyttäjän tekemät arvostelut
		 *	Return: NULL = ei löytynyt arvosteluja TAI kokoelma arvostelu-objekteja
		 */
		public static function findByUser($id){
			$query = DB::connection()->prepare(
				"SELECT R.id, R.comp_id, R.review, R.rating, R.datum, C.brand, C.name FROM Reviews AS R, Computers AS C WHERE R.user_id=:id AND C.id=R.comp_id"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$reviews = null;
			foreach($rows as $row) {
				$reviews[] = new Review(array(
					'id'			=> $row['id'],
					'comp_id'		=> $row['comp_id'],
					'review'		=> $row['review'],
					'rating'		=> $row['rating'],
					'datum'			=> self::formatDatum($row['datum']),
					'comp_brand'	=> $row['brand'],
					'comp_name'		=> $row['name']
				));
			}
			return $reviews;
		}

		/*
		 *	Tallenna arvostelu-objektin tiedot tietokantaan
		 */
		public static function save($attrArray){
			$query = DB::connection()->prepare(
				"INSERT (user_id, comp_id, review, rating, datum) 
				VALUES (:user_id, :comp_id, :review, :rating, :datum) 
				INTO Reviews"
			);
			$query->execute($attrArray);
		}

	}
