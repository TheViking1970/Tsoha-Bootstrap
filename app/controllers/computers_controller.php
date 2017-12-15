<?php

  class ComputersController extends BaseController{

    /*
     *  Haetaan kaikki tietokoneet tietokannasta ja listataan ne näytölle.
     */
    public static function computers_list(){
      $computers = Computer::all();
      if($computers) {
        View::make("computers_list.html", array('computers'=>$computers));
      } else {
        Redirect::to('/error', array('message' => 'No computers found!'));
      }
    }

    /*
     *  Näytetään yhden tietokoneen tiedot ja muut siihen liittyva data käyttäjälle.
     *  - haetaan ensin haluttu tietokone, ja jos löytyi niin
     *  - haetaan kyseisen tietokoneen lokitietoja (eli kuka on lisännyt koneen järjestelmään ja ketkä ovat tehneet muokkauksia)
     *  - lokitietojen ensimmäinen käyttäjä on lisääjä joten otetaan erikseen ne tiedot ja poistetaan tietue muista lokitiedoista
     *  - haetaan tietokoneen arvostelut
     *  Jos kaikki onnistui, niin näytetään tiedot käyttäjälle,
     *  muuten virheilmoitus.
     */
    public static function computer_view($id, $error = null){
      $computer = Computer::find($id);
      if($computer) {
        $user = null;
        $logs = Log::findByComputer($id);
        if($logs) {
          // first user in log for the computer is the one that added the computer
          $user = User::find($logs[0]->user_id);
          $computer->datum = $logs[0]->datum;
          unset($logs[0]);
        }
        $reviews = Review::findByComputer($id);
        View::make("computer_view.html", array('computer'=>$computer, 'user'=>$user, 'logs'=>$logs, 'reviews'=>$reviews, 'error'=>$error));//, 'reviews'=>$reviews));
      } else {
        Redirect::to('/error', array('message' => 'Computer not found!'));
      }
    }

    /*
     *  Näytetään tietokoneen muokkaamissivua (sama html kuin lisäämissivulla).
     *  Ensin tarkistetaan, että käyttäjä on kirjautunut.
     */
    public static function computer_edit($id){
      self::check_logged_in();
      $computer = Computer::find($id);
      View::make("computer_add.html", array('mode'=>'edit', 'computer'=>$computer));
    }

    /*
     *  Näytetään tietokoneen lisäämissivua.
     *  Ensin tarkistetaan, että käyttäjä on kirjautunut.
     */
    public static function computer_add(){
      self::check_logged_in();
      View::make("computer_add.html", array('mode'=>'add'));
    }

    /*
     *  Käsitellään tietokoneen lisäämistä.
     *  Tarkistetaan, että käyttäjä on kirjautunut.
     *  Luodaan tietokone-objekti POST-tiedoista, ja listään tietokone tietokantaan.
     *  Virhetilanteessa näytetään lisäämissivu uudestaan ja missä kentissä ongelmakohdat olivat.
     */
    public static function do_computer_add(){
      self::check_logged_in();
      $computer = self::computerFromPOST();
      $errors = $computer->add();
      if($errors) {
        View::make("computer_add.html", array('mode'=>'add', 'computer'=>$computer, 'error'=>$computer->errors));
      }
      else {
        Redirect::to('/thank_you', array('reason' => 'addComputer'));
      }
    }

    /*
     *  Käsitellään tietokoneen muokkaamista.
     *  Tarkistetaan, että käyttäjä on kirjautunut.
     *  Luodaan tietokone-objekti POST-tiedoista, ja päivitetään tietokone tietokannassa.
     *  Virhetilanteessa näytetään muokkaamissivu uudestaan ja missä kentissä ongelmakohdat olivat.
     */
    public static function do_computer_edit($id) {
      self::check_logged_in();
      $computer = self::computerFromPOST();
      $computer->id = $id;
      $errors = $computer->update();
      if($errors) {
        View::make("computer_add.html", array('mode'=>'edit', 'computer'=>$computer, 'error'=>$computer->errors));
      }
      else {
        Redirect::to('/thank_you', array('reason' => 'ediComputer'));
      }
    }

    /*
     *  Käsitellää tietokoneen poistamista.
     *  Ensin tarkistetaan, että käyttäjä on kirjautunut.
     *  Virhetilanteissa näytetään virheilmoitus.
     */
    public static function computer_delete($id){
      self::check_logged_in();
      $computer = Computer::find($id);
      if(!$computer) {
        Redirect::to('/computer_view/'.$id, array('reason' => 'notFoundError'));
        return;
      }
      $errors = $computer->delete();
      if($errors) {
        Redirect::to('/computer_view/'.$id, array('reason' => 'deleteComputer'));
        return;
      }
      Redirect::to('/thank_you', array('reason' => 'deleteComputer'));
    }

    /*
     *  Apumetoodi jolla saadaan helposti luotua tietokone-objektin http POST-tiedoista
     */
    public static function computerFromPOST() {
      $computer = new Computer(array(
        'brand'     => $_POST['brand'],
        'name'      => $_POST['name'],
        'imgurl'    => $_POST['imgurl'],
        'infotext'  => $_POST['infotext'],
      ));
      return $computer;
    }

  }
