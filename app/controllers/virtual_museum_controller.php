<?php

  class VirtualMuseumController extends BaseController{

    public static function home(){
      View::make('home.html');
    }

    public static function computers_list(){
      $computers = Computer::all();
      View::make("computers_list.html", array('computers'=>$computers));
    }

    public static function computer_view($id){
      $computer = Computer::find($id);
      $user = User::getAdderOfComputer($id);
      $edits = Log::find($id);
      $reviews = Review::find($id);
      View::make("computer_view.html", array('computer'=>$computer, 'user'=>$user, 'edits'=>$edits));//, 'reviews'=>$reviews));
    }

    public static function computer_edit($id){
      $computer = Computer::find($id);
      View::make("computer_edit.html", array('computer'=>$computer));
    }

    public static function computer_add(){
      View::make("computer_add.html");
    }

    public static function do_computer_add(){
      $p = $_POST;
      $computer = new Computer(array(
        'brand'     => $p['brand'],
        'name'      => $p['name'],
        'imgurl'    => $p['imgurl'],
        'infotext'  => $p['infotext'],
      ));
      $retValue = $computer->save($p);
      if($retValue) {
        $message=array('reason'=>1);
        View::make("thank_you.html", array('message'=>$message));
      }
      else {
        View::make("computer_add.html", array('computer'=>$p, 'error'=>2));
      }
    }

    public static function users_list(){
      $users = User::all();
      View::make("users_list.html", array('users'=>$users));
    }

    public static function user_view($id){
      $user = User::find($id);
      $adds = Log::find($id);
      $reviews = Review::find($id);
      View::make("user_view.html", array('user'=>$user));
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

    public static function logout(){
      View::make("logout.html");
    }

    public static function profile_edit(){
      $id = 1;
      $user = User::find($id);
      View::make("profile_edit.html", array('user'=>$user));
    }

  }
