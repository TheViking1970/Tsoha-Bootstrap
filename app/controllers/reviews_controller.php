<?php

  class ReviewsController extends BaseController{

    //	Not implemented YET.
    public static function review_add(){
      self::check_logged_in();
      View::make("review_add.html");
    }

  }
