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
		//	kokoelma validointifunktioista
		public $validators = array('_rating', '_review');
		//	lisäämisessä/muokaamisessa tapahtuville virheille varattu muuttuja
		public $errors = array();
	  	
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
				"SELECT * FROM Reviews ORDER BY id ASC"
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
				"SELECT R.id, R.user_id, R.review, R.rating, R.datum, U.name FROM Reviews AS R, Users AS U WHERE R.comp_id=:id AND U.id=R.user_id ORDER BY R.datum ASC"
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
				"SELECT R.id, R.comp_id, R.review, R.rating, R.datum, C.brand, C.name FROM Reviews AS R, Computers AS C WHERE R.user_id=:id AND C.id=R.comp_id ORDER BY R.datum ASC"
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
		public function add(){
			$errorCount = $this->validate();
			if($errorCount) {
				return $this->errors;
			}
			$query = DB::connection()->prepare(
				"INSERT INTO Reviews (user_id, comp_id, review, rating, datum) 
				VALUES (:user_id, :comp_id, :review, :rating, :datum)"
			);
			$query->execute(array(
				'user_id'	=> $_SESSION['userId'],
				'comp_id'	=> $this->comp_id,
				'review'	=> $this->review,
				'rating'	=> $this->rating,
				'datum'		=> self::formatDatum($this->datum)
			));
		}

		/*
		 *	Validointifunktio
		 *	Käy läpi kaikki validators-muuttujassa olevat validointifunktot,
		 *	ja kirjaa kaikki mahdolliet puutteet ja ongelmat jatkokäsittelyä varten
		 *	Return: virheiden lukumäärä
		 */
		public function validate() {
			$errorCount = 0;
			$errorArray = array();
			foreach($this->validators as $validator) {
				$errorField = $this->{'validate'.$validator}();
				if($errorField) {
					$errorArray[$errorField] = true;
					$errorCount++;
					$errorArray['errors'] = $errorCount;
				}
			}
			$this->errors = $errorArray;
			return $errorCount;
 		}

 		/*
 		 *	Validoidaan arvioinnin numeerinen osa
 		 *	Return: virhetilanteessa palautetaan kentän nimi
 		 */
		public function validate_rating() {
			$rating = intval($this->rating);
			$this->rating = $rating;
			if($rating<1 || $rating>5) {
				return 'rating';
			}

		}

 		/*
 		 *	Validoidaan arvioinnin tekstiosa
 		 *	Return: virhetilanteessa palautetaan kentän nimi
 		 */
		public function validate_review() {
			$review = trim($this->review);
			$this->review = $review;
			if(strlen($review) < 10) {
				return 'review';
			}
		}

	}
