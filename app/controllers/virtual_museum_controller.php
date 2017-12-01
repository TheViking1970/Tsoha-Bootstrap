<?php

  class VirtualMuseumController extends BaseController{

    /*
     *  Näytä etusivu
     */
    public static function home(){
      View::make('home.html');
    }

    /*
     *  Näytä kiitos-sivu.
     *  Tätä käytetään eri tilaanteissa, näyttämään että haluttu toiminto on onnistunut.
     */
    public static function thank_you(){
      View::make('thank_you.html');
    }


  }
