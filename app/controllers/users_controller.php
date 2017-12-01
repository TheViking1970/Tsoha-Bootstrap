<?php

  class UsersController extends BaseController{

    /*
     *  Hakee kaikki käyttäjät tietokannasta ja listaa ne näytölle.
     *  Mikäli ei käyttäjiä löydy annetaan virheilmoitus.
     */
    public static function users_list(){
      $users = User::all();
      if($users) {
        View::make("users_list.html", array('users'=>$users));
      } else {
        View::make("error.html", array('message'=>'No Users found!'));
      }
    }

    /*
     *  Näyttää yhden käyttäjän tiedot.
     *  Tarkistaa mikäli kyseinen käyttäjä on sama kuin kirjautunut käyttäjä,
     *  jos on niin näytetään myös editointi-nappia.
     *  Jos haettu käyttäjä ei läydy järjestelmästä, niin näytetään virheilmoitus.
     */
    public static function user_view($id){
      $loggedInUser = self::get_user_logged_in();
      $user = User::find($id);
      if($loggedInUser && $loggedInUser->id == $id) {
        $ownprofile = true;
      } else {
        $ownprofile = false;
      }
      if($user) {
        $logs = Log::findbyUser($id);
        $reviews = Review::findByUser($id);
        View::make("user_view.html", array('user'=>$user, 'logs'=>$logs, 'reviews'=>$reviews, 'ownprofile'=>$ownprofile));
      } else {
        View::make("error.html", array('message'=>'User not found!'));
      }
    }

    /*
     *  Näytetään rekisteröitymislomake käyttäjälle
     */
    public static function register(){
      View::make("register.html");
    }

    /*
     *  Käsitellään rekisteröitymislomakkeen tiedot.
     *  - salasna on annettu kaksi kertaa identtisesti
     *  - käyttäjänimi on vapaa
     *  - myös rudimentäärinen robootti-tarkistus
     */
    public static function do_register(){
      if($_POST['check1'] != $_POST['check2'] * $_POST['check2']) {
          View::make("register.html", array('error'=>'Are you a robot?! Error in robot-check!'));
      }
      if(User::nameInUse($_POST['name'])) {
        View::make("register.html", array('error'=>'Username already taken.'));
      } else {
        if($_POST['pass1'] == $_POST['pass2']) {
          $user = self::userFromPOST();
          $error = $user->add();
          View::make("login.html", array('reason'=>'newUser'));
        } else {
          View::make("register.html", array('error'=>'Passwords do not match!'));
        }
      }
    }

    /*
     *  Näytetään kirjautumislomake
     */
    public static function login(){
      View::make("login.html");
    }

    /*
     *  Käsitellään kirjautumista.
     *  Mikäli autentikointi onnistuu, niin käyttäjä kirjaudutaan järjestelmään,
     *  muuten näytetään virheilmoitus
     */
    public static function do_login(){
      $errors = User::authenticate($_POST['name'],$_POST['password']);
      if($errors) {
        View::make("login.html", array('error'=>'loginError'));
      } else {
        View::make('home.html');
      }
    }

    /*
     *  Käsitellään uloskirjautumista.
     */
    public static function logout(){
      unset($_SESSION['userId']);
      View::make("thank_you.html", array('reason'=>'logout'));
    }

    /*
     *  Näytetään käyttäjän omat profiilitiedot muokkausta varten.
     *  Jos kirjautunut käyttäjä ei enää löydy tietokannasta näytetään virheilmoitus.
     */
    public static function profile_edit(){
      self::check_logged_in();
      $user = User::find($_SESSION['userId']);
      if($user) {
        View::make("profile_edit.html", array('user'=>$user));
      } else {
        View::make("error.html", array('message'=>'Profile not found!'));
      }
    }

    /*
     *  Käsitellään profiilisivun muokkausta.
     *  Tarkistetaan ensin, että käyttäjä on kirjautuut sivustolle.
     *  Mikäli käyttäjä yrittää muuttaa salasanaa, niin tarkistetaan, että molemmat syötteet ovat identtisiä.
     *  Virhetilanteessa näytetään editointisivulla mitkä kentät aiheuttivat virheen,
     *  muuten näytetään käyttäjälle päivitetty profiilisivu.
     */
    public static function do_profile_edit() {
      self::check_logged_in();
      $user = User::find($_SESSION['userId']);
      $error = $user->update($_POST['motto'], $_POST['pass1'], $_POST['pass2']);      
      if($error) {
        View::make("profile_edit.html", array('user'=>$user, 'error'=>'Passwords did not match, profile NOT updated!'));
      } else {
        self::user_view($user->id);
      }
    }

    /*
     *  Apumetoodi jolla saadaan käyttäjä-objekti luotua helposti http POST-tiedoista.
     */
    public static function userFromPOST() {
      $user = new User(array(
        'name'      => $_POST['name'],
        'password'  => $_POST['pass1'],
        'motto'     => '',
        'datum'     =>  date('d.m.Y')
      ));
      return $user;
    }

  }
