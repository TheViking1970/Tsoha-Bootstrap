<?php

	class Computer extends BaseModel{
		/*
		 *	id 			= tietokoneen tietokantatietueen id
		 *	brand 		= tietokoneen merkki
		 *	name 		= tietokoneen nimi/malli
		 *	imgurl 		= osoite tietokoneen kuvaan
		 *	infotext 	= yleistä tekstiä tietokoneesta
		 */
		public $id, $brand, $name, $imgurl, $infotext;
		/*
		 *	Tilapäisiä muuttujia
		 *	uid 	= lisääjän id
		 *	uname 	= lisääjän nimi
		 *	datum 	= lisäyspäivämäärä
		 */
		public $uid, $uname, $datum;
		//	kokoelma validointifunktioista
		public $validators = array('_brand', '_name', '_imgurl', '_infotext');
		//	lisäämisessä/muokaamisessa tapahtuville virheille varattu muuttuja
		public $errors = array();
	  	
	  	/*
	  	 *	Objektin konstruktori
	  	 */
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

	  	/*
	  	 *	Hae kaikki tietokoneet tietokannasta ja lisää siihen lisääjän tiedot ja päivämäärä
	  	 *	Return: NULL = ei löytnyt tietokoneita TAI kokoelma tietokone-objekteja
	  	 */
		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT C.id, C.brand, C.name, U.id AS uid, U.name AS uname, L.datum FROM Computers AS C 
					LEFT JOIN (SELECT * FROM (SELECT *, rank() OVER (PARTITION BY comp_id ORDER BY id ASC) AS pos FROM Logs) AS tmpLogs WHERE pos=1) AS L ON C.id=L.comp_id 
					LEFT JOIN Users AS U ON L.user_id=U.id
					ORDER BY C.id ASC"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$computers = null;
			foreach($rows as $row) {
				$computers[] = new Computer(array(
					'id'		=> $row['id'],
					'brand'		=> $row['brand'],
					'name'		=> $row['name'],
					'uid'		=> $row['uid'],
					'uname'		=> $row['uname'],
					'datum'		=> self::formatDatum($row['datum'])
				));
			}
			return $computers;
		}

		/*
		 *	Haetaan tietty tietokone järjestelmästä
		 *	Return: NULL = ei löytynyt TAI tietokone-objekti
		 */
		public static function find($id){
			$query = DB::connection()->prepare(
				"SELECT * FROM Computers WHERE id=:id LIMIT 1"
			);
			$query->execute(array('id' => $id));
			$row = $query->fetch();
			$computer = null;
			if($row) {
				$computer = new Computer(array(
					'id'		=> $row['id'],
					'brand'		=> $row['brand'],
					'name'		=> $row['name'],
					'imgurl'	=> $row['imgurl'],
					'infotext'	=> $row['infotext']
				));
			}
			return $computer;
		}
		
		/*
		 *	Listään uusi tietokone tietokantaan
		 *	Return: virheiden lukumäärä TAI NULL
		 */
		public function add(){
			$errorCount = $this->validate();
			if($errorCount) {
				return $this->errors;
			}
			$query = DB::connection()->prepare(
				"INSERT INTO Computers (brand, name, imgurl, infotext) 
				VALUES (:brand, :name, :imgurl, :infotext) 
				RETURNING id"
			);
			$query->execute(array(
				'brand' 	=> $this->brand,
				'name' 		=> $this->name,
				'imgurl'	=> $this->imgurl,
				'infotext' 	=> $this->infotext,
			));
			$row = $query->fetch();
			if($row) {
				$query = DB::connection()->prepare(
					"INSERT INTO Logs (user_id, comp_id, datum) VALUES (:uid, :cid, :datum)"
				);
				$query->execute(array(
					'uid' 	=> $_SESSION['userId'],
					'cid' 	=> $row['id'],
					'datum' => date('d.m.Y') 
				));
			}
		}

		/*
		 *	Päivitetään olemassa olevan tietokoneen tiedot tietokannassa.
		 *	Return: virheiden lukumäärä
		 */
		public function update() {
			$errorCount = $this->validate();
			if($errorCount != 0) {
				return $this->errors;
			}
			$query = DB::connection()->prepare(
				"UPDATE Computers SET brand=:brand, name=:name, imgurl=:imgurl, infotext=:infotext WHERE id=:id RETURNING id"
			);
			$query->execute(array(
				'id' 		=> $this->id,
				'brand' 	=> $this->brand,
				'name' 		=> $this->name,
				'imgurl' 	=> $this->imgurl,
				'infotext' 	=> $this->infotext,
			));
			$row = $query->fetch();
			if($row) {
				Log::insert($this->id, $_SESSION['userId']);
			} else {
				$this->error['errors'] = 1;
				$errorCount++;
			}
			return $errorCount; 
		}

		/*
		 *	Poistetaan tietokone tietokannasta
		 *	Return: virhekoodi TRUE = postaminen epäonnistui TAI  FALSE = tietokone poistettiin
		 */
		public function delete() {
			// posta ensin kaikki lokitiedot liittyen tähän tietokoneeseen, koska Foreign Key!
			/*
			$query = DB::connection()->prepare(
				"DELETE FROM Logs WHERE comp_id=:cid"
			);
			$query->execute(array('cid' => $this->id));
			*/
			// nyt voidaan poistaa itse tietokone
			$query = DB::connection()->prepare(
				"DELETE FROM Computers WHERE id=:id"
			);
			$error = $query->execute(array('id' => $this->id));
			return !$error; 
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
 		 *	Validoidaan tietokoneen merkkiä
 		 *	Return: virhetilanteessa palautetaan kentän nimi
 		 */
		public function validate_brand() {
			$brand = trim($this->brand);
			$this->brand = $brand;
			if(strlen($brand) < 3) {
				return 'brand';
			}
		}

 		/*
 		 *	Validoidaan tietokoneen nimeä/mallia
 		 *	Return: virhetilanteessa palautetaan kentän nimi
 		 */
		public function validate_name() {
			$name = trim($this->name);
			$this->name = $name;
			if(strlen($name) < 1) {
				return 'name';
			}
		}

		public function validate_imgurl() {
			// no validation currently
		}

 		/*
 		 *	Validoidaan tietokoneen yleinen tekstiosa
 		 *	Return: virhetilanteessa palautetaan kentän nimi
 		 */
		public function validate_infotext() {
			$infotext = trim($this->infotext);
			$this->infotext = $infotext;
			if(strlen($infotext) < 50) {
				return 'infotext';
			}
		}

	}
