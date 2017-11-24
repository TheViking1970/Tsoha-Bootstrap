<?php

  class BaseController{

    public static function get_user_logged_in(){
      if(isset($_SESSION['userId'])){
        $userId = $_SESSION['userId'];
        $user = User::find($userId);
        return $user;
      }
      return null;
    }

    public static function check_logged_in(){
      if(!isset($_SESSION['userId'])) {
        Redirect::to('/');
      }
    }

  }
