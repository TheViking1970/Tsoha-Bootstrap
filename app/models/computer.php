<?php

	class Computer extends BaseModel{
		public $id, $brand, $name, $imgurl, $infotext;
		public $validators = array('_brand', '_name', '_imgurl', '_infotext');
		public $errors = array();
	  	
	  	public function __construct($attr) {
	  		parent::__construct($attr);
	  	}

		public static function all(){
			$query = DB::connection()->prepare(
				"SELECT Computers.id, brand, name, imgurl, infotext FROM Computers"// JOIN Logs ON Computers.id=Logs.comp_id"
				//"SELECT Computers.id, brand, Computers.name as name, imgurl, infotext, Users.id as uid, Users.name as uname, Logs.datum FROM Computers JOIN Logs ON Computers.id=Logs.comp_id JOIN Logs.user_id=Users.id"
			);
			$query->execute();
			$rows = $query->fetchAll();
			$returnArray = array();
			foreach($rows as $row) {
				$returnArray[] = new Computer(array(
					'id'		=> $row['id'],
					'brand'		=> $row['brand'],
					'name'		=> $row['name'],
					'imgurl'	=> $row['imgurl'],
					'infotext'	=> $row['infotext']/*,
					'uid'		=> $row['uid'],
					'uname'		=> $row['uname'],
					'datum'		=> $row['datum']*/
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
		
		public function add(){
			$errorCount = $this->validate();
			if($errorCount!=0) {
				return $this->errors;
			}
			$query = DB::connection()->prepare(
				"INSERT INTO Computers (brand, name, imgurl, infotext) 
				VALUES (:brand, :name, :imgurl, :infotext) 
				RETURNING id"
			);
			$query->execute(array(
				'brand' => $this->brand,
				'name' => $this->name,
				'imgurl' => $this->imgurl,
				'infotext' => $this->infotext,
			));
			$sqlReturn = $query->fetch();
			if($sqlReturn) {
				$query = DB::connection()->prepare(
					"INSERT INTO Logs (user_id, comp_id, datum) VALUES (:uid, :cid, :datum)"
				);
				$logInfo = array(
					'uid' => 1,
					'cid' => $sqlReturn['id'],
					'datum' => date('d.m.Y') // HUOM! Postgre DATE-tyyppi m.d.y, mutta joki asetus muuttaa sen muotoon d.m.y!!! 
				);
				$query->execute($logInfo);
			}
			return;
		}

		public function update() {
			$query = DB::connection()->prepare(
				"UPDATE Computers SET brand=:brand, name=:name, imgurl=:imgurl, infotext=:infotext WHERE id=:id"
			);
			$query->execute(array(
				'id' => $this->id,
				'brand' => $this->brand,
				'name' => $this->name,
				'imgurl' => $this->imgurl,
				'infotext' => $this->infotext,
			));
			$error = $query->fetch();
			return $error?1:0; // ZERO rows updated => ERROR
		}

		public function delete() {
			$query = DB::connection()->prepare(
				"DELETE FROM Logs WHERE comp_id=:cid"
			);
			$query->execute(array('cid' => $this->id));

			$query = DB::connection()->prepare(
				"DELETE FROM Computers WHERE id=:id"
			);
			$query->execute(array('id' => $this->id));
			$rows = $query->fetch();
			return $rows;
		}

		public function validate() {
			$errorCount = 0;
			$errorArray = array();
			foreach($this->validators as $validator) {
				$retValue = $this->{'validate'.$validator}();
				if($retValue) {
					$errorArray[$retValue] = 1;
					$errorCount++;
					$errorArray['errors'] = $errorCount;
				}
			}
			$this->errors = $errorArray;
			return $errorCount;
 		}

		public function validate_brand() {
			$brand = $this->brand;
			$brand = trim($brand);
			$this->brand = $brand;
			if(strlen($brand)<3) {
				return 'brand';
			}
		}

		public function validate_name() {
			$name = $this->name;
			$name = trim($name);
			$this->name = $name;
			if(strlen($name)<1) {
				return 'name';
			}
		}

		public function validate_imgurl() {
			// no validation currently
		}

		public function validate_infotext() {
			$infotext = $this->infotext;
			$infotext = trim($infotext);
			$this->infotext = $infotext;
			if(strlen($infotext)<50) {
				return 'infotext';
			}
		}

		public static function test(){
			return 'Hello World!';
		}

	}
