<?php

  class VirtualMuseumController extends BaseController{

    public static function home(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('home.html');
    }

    public static function computers_list(){
      View::make("computers_list.html");
    }

    public static function computer_view(){
      View::make("computer_view.html");
    }

    //does not exist yet
    public static function computer_edit(){
      View::make("computer_edit.html");
    }

    public static function users_list(){
      View::make("users_list.html");
    }

    public static function user_view(){
      View::make("user_view.html");
    }

    //does not exist yet
    public static function review_add(){
      View::make("review_add.html");
    }

    public static function register(){
      View::make("register.html");
    }

    public static function login(){
      View::make("login.html");
    }

    //does not exist yet
    public static function logout(){
      View::make("logout.html");
    }

    public static function profile(){
      View::make("profile.html");
    }

    //does not exist yet
    public static function profile_edit(){
      View::make("profile_edit.html");
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      // echo 'Hello World!';
      View::make("helloworld.html");
    }
  }
