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
      parent::check_logged_in();
      $computer = Computer::find($id);
      View::make("computer_add.html", array('mode'=>'edit', 'computer'=>$computer));
    }

    public static function computer_add(){
      parent::check_logged_in();
      View::make("computer_add.html", array('mode'=>'add'));
    }

    public static function do_computer_add(){
      parent::check_logged_in();
      $p = $_POST;
      $computer = new Computer(array(
        'brand'     => $p['brand'],
        'name'      => $p['name'],
        'imgurl'    => $p['imgurl'],
        'infotext'  => $p['infotext'],
      ));
      $errors = $computer->add();
      $mode = 'add';
      if($errors > 0) {
        View::make("computer_add.html", array('mode'=>$mode, 'computer'=>$computer, 'error'=>$computer->errors));
      }
      else {
        View::make("thank_you.html", array('reason'=>$mode.'Computer'));
      }
    }

    public static function do_computer_edit($id) {
      parent::check_logged_in();
      $p = $_POST;
      $p['id'] = $id;
      $computer = new Computer(array(
        'id'        => $id,
        'brand'     => $p['brand'],
        'name'      => $p['name'],
        'imgurl'    => $p['imgurl'],
        'infotext'  => $p['infotext'],
      ));
      if($id==0) {
        $errors = $computer->add();
        $mode = 'add';
      } else {
        $errors = $computer->update();
        $mode = 'edit';
      }
      if($errors > 0) {
        View::make("computer_add.html", array('mode'=>$mode, 'computer'=>$computer, 'error'=>$computer->errors));
      }
      else {
        View::make("thank_you.html", array('reason'=>$mode.'Computer'));
      }
    }

    public static function computer_delete($id){
      parent::check_logged_in();
      $computer = Computer::find($id);
      if(!$computer) {
        computer_view($id, array('error'=>'notfoundError'));
        return;
      }
      $errors = $computer->delete();
      if($errors) {
        computer_view($id, array('error'=>'deleteError'));
        return;
      }
      View::make("thank_you.html", array('reason'=>'deleteComputer'));
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

    public static function do_login(){
      $p = $_POST;
      $errors = User::login($p['name'],$p['password']);
      if($errors) {
        View::make("login.html", array('error'=>'loginError'));
      } else {
        View::make('home.html');
      }
    }

    public static function logout(){
      unset($_SESSION['userId']);
      View::make("thank_you.html", array('reason'=>'logout'));
    }

    public static function profile_edit(){
      $id = 1;
      $user = User::find($id);
      View::make("profile_edit.html", array('user'=>$user));
    }

  }
