<?php

require 'app/models/computer.php';

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
      echo "Tämä on etusivu!";
    }

    /*
    public static function sandbox(){
      // Testaa koodiasi täällä
      //echo 'Hello World!';
      View::make("helloworld.html");
    }
    */

    public static function sandbox(){
      $compOne = Computer::find(1);
      $compAll = Computer::all();
      // Kint-luokan dump-metodi tulostaa muuttujan arvon
      Kint::dump($compOne);
      Kint::dump($compAll);
    }

  }
