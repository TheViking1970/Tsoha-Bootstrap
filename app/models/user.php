<?php

	class User extends BaseModel{
		/*
		 *	id 			= käyttäjän tietokantatietueen id
		 *	nameInUse 	= käyttäjän nimi
		 *	password 	= käyttäjän salsana, käytetään vain kun käsitellään salasana-muutoksia ja uuden käyttäjän rekisteröinniä
		 *	motto 		= käyttäjän antama motto/slogan
		 *	datum 		= rekisteröitymispäivämäärä
		 */
		public $id, $name, $password, $motto, $datum; 	
	  	
		/*
		 *	Objektin konstruktori
		 */
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

	  	/*
	  	 *	Hae kaikki käyttäjät tietokannasta.
	  	 *	Return:	NULL TAI kokoelma käyttäjä-objekteja
	  	 */
		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT * FROM Users"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$users = null;
			foreach($rows as $row) {
				$users[] = new User(array(
					'id'	=> $row['id'],
					'name'	=> $row['name'],
					'datum'	=> self::formatDatum($row['datum'])
				));
			}
			return $users;
		}

		/*
		 *	Hae käyttäjä id:n perusteella
		 *	Return: NULL TAI käyttäjä-objekti
		 */
		public static function find($id){
			$query = DB::connection()->prepare(
				"SELECT * FROM Users WHERE id=:id LIMIT 1"
			);
			$query->execute(array('id' => $id));
			$row = $query->fetch();
			$user = null;
			if($row) {
				$user = new User(array(
					'id'		=> $row['id'],
					'name'		=> $row['name'],
					'password'	=> $row['password'],
					'motto'		=> $row['motto'],
					'datum'		=> self::formatDatum($row['datum'])
				));
			}
			return $user;
		}
		
		/*
		 *	Tarkistetaan onko haluttu käyttäjänimi varattu vai vapaa
		 *	Return: TRUE = nimi käytössä TAI FALSE = nimi vapaa
		 */
		public static function nameInUse($name){
			$query = DB::connection()->prepare(
				"SELECT id FROM Users WHERE name=:name LIMIT 1"
			);
			$query->execute(array('name' => $name));
			$inUse = $query->fetch();
			return $inUse?true:false;
		}

		/*
		 *	Lisätään käyttäjä-objektin tiedot uutena käyttäjänä tietokantaan
		 */
		public function add(){
			$query = DB::connection()->prepare(
				"INSERT INTO Users (name, password, motto, datum) 
				VALUES (:name, :password, :motto, :datum)"
			);
			$query->execute(array(
				'name'		=> $this->name,
				'password'	=> $this->password,
				'motto'		=> $this->motto,
				'datum'		=> self::formatDatum($this->datum)
			));
		}

		/*
		 *	Päivitetään olemassa oleva käyttäjä tietokannassa uusilla tiedoilla
		 *	Return: virhekoodi TRUE TAI NULL
		 */
		public function update($motto, $pass1, $pass2){
			$this->motto = $motto;
			if($pass1 !== $pass2) {
				return true; 
			}
			$this->password = $pass1;
			$query = DB::connection()->prepare(
				"UPDATE Users SET motto=:motto, password=(CASE :pass WHEN '' THEN password ELSE :pass END) WHERE id=:id"
			);
			$query->execute(array(
				'id'	=> $this->id,
				'motto'	=> $this->motto,
				'pass'	=> $this->password
			));
		}

		/*
		 *	Autentikoidaan käyttäjän syöttämät tiedot tietokannassa oleviin
		 *	Return: virhekoodi TRUE = tapahtui virhe TAI FALSE = kaikki kunnossa
		 */
		public static function authenticate($username, $password) {
			$query = DB::connection()->prepare(
				"SELECT id FROM Users WHERE LOWER(name)=LOWER(:name) AND password=:password LIMIT 1"
			);
			$query->execute(array('name' => $username, 'password' => $password));
			$row = $query->fetch();
			$id = isset($row['id'])?$row['id']:null;
			$_SESSION['userId'] = $id;
			return $id?false:true; // if no id ==> ERROR=1 !!
		}

		/*
		 *	Haetaan käyttäjä joka on lisännyt tietyn tietokoneen järjestelmään
		 *	Return: NULL TAI käyttäjä-objekti
		 */
		public static function getAdderofComputer($id){
			$query = DB::connection()->prepare(
				"SELECT Users.id AS id, Users.name AS name, Logs.datum AS datum FROM Users, Logs WHERE Logs.comp_id=:id AND Users.id=Logs.user_id ORDER BY Logs.id ASC LIMIT 1"
			);
			$query->execute(array('id' => $id));
			$row = $query->fetch();
			if($row) {
				$user = new User(array(
					'id'		=> $row['id'],
					'name'		=> $row['name'],
					'datum'		=> self::formatDatum($row['datum'])
				));
				return $user;
			} else {
				return null;
			}
		}

	}
