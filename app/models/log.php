<?php

	class Log extends BaseModel{
		/*
		 *	id 		= lokitiedon tietokantatietueen id
		 *	comp_id = tietokoneen id johon liittyy
		 *	user_id = käyttäjän id joka on tehnyt lisäyksen/muutoksen
		 *	datum 	= päiväys
		 */
		public $id, $comp_id, $user_id, $datum; 
		/*
		 *	Tilapäisiä muuttujia
		 *	comp_brand 	= lisätyn/muokatu tietokoneen merkki
		 *	comp_name 	= lisätyn/muokatun tietokoneen nimi/malli
		 *	user_name 	= lisääjän/muokkaajan nimi
		 */
		public $comp_brand, $comp_name, $user_name;	
	  	
	  	/*
	  	 *	Objektin konstruktori
	  	 */
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

	  	/*
	  	 *	Haetaan kaikki lokitiedot
	  	 *	Tätä ei käytetä tällä hetkellä, mutta jos järjestelmään lisätään ylläpitotyökaluja tämä tulee tarpeen
	  	 *	Return: NULL jos ei lokitietoja TAI kokoelma loki-objekteja
	  	 */
		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT * FROM Logs ORDER BY id ASC"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$logs = null;
			foreach($rows as $row) {
				$logs[] = new Log(array(
					'id'		=> $row['id'],
					'comp_id'	=> $row['comp_id'],
					'user_id'	=> $row['user_id'],
					'datum'		=> $row['datum']
				));
			}
			return $logs;
		}

		/*
		 *	Lisätään lokitietoja tietokantaan
		 */
		public static function insert($comp_id, $user_id) {
			$query = DB::connection()->prepare(
				"INSERT INTO Logs (comp_id, user_id, datum) VALUES (:cid, :uid, :datum)"
			);
			$query->execute(array(
				'cid'	=> $comp_id,
				'uid'	=> $user_id,
				'datum'	=> date('d.m.Y')
			));
		}

		/*
		 *	Haetaan tietyn tietokoneen kaikki lokitiedot
		 *	Return: NULL = ei lokitietoja TAI kokoelma loki-objekteja (sis. käyttäjänimen)
		 */
		public static function findByComputer($id){
			$query = DB::connection()->prepare(
				"SELECT L.user_id, L.datum, U.name FROM Logs AS L, Users AS U WHERE L.comp_id=:id AND U.id=L.user_id ORDER BY L.id ASC"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$logs = null;
			foreach($rows as $row) {
				$logs[] = new Log(array(
					'comp_id'		=> $id,
					'user_id'		=> $row['user_id'],
					'datum'			=> self::formatDatum($row['datum']),
					'user_name'		=> $row['name']
				));
			}
			return $logs;
		}
		
		/*
		 *	Haetaan tietyn käyttäjän kaikki lokitiedot
		 *	Return: NULL = ei lokitietoja TAI kokoelma loki-ojekteja (sis. tietokoneen merkki ja nimi/malli)
		 */
		public static function findByUser($id){
			$query = DB::connection()->prepare(
				"SELECT L.comp_id, L.datum, C.brand, C.name FROM Logs AS L, Computers AS C WHERE L.user_id=:id AND C.id=L.comp_id ORDER BY L.id ASC"
			);
			$query->execute(array('id' => $id));
			$rows = $query->fetchAll();
			$logs = null;
			foreach($rows as $row) {
				$logs[] = new Log(array(
					'comp_id'		=> $row['comp_id'],
					'user_id'		=> $id,
					'datum'			=> self::formatDatum($row['datum']),
					'comp_brand'	=> $row['brand'],
					'comp_name'		=> $row['name']
				));
			}
			return $logs;
		}

		/*
		 *	Tallenna lokitiedot tietokantaan
		 */
		public function save(){
			$query = DB::connection()->prepare(
				"INSERT INTO Logs (comp_id, user_id, datum) 
				VALUES (:comp_id, :user_id, :datum)"
			);
			$query->execute(array(
				'comp_id' 	=>	$this->comp_id,
				'user_id'	=>	$this->user_id,
				'datum' 	=>	$this->datum
			));
		}

	}
