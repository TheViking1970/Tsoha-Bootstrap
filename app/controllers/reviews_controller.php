<?php

  class ReviewsController extends BaseController{

    public static function review_add($id){
      self::check_logged_in();
      $computer = Computer::find($id);
      if($computer) {
      	$review = new Review(array(
      		'user_id'		=> $_SESSION['userId'],
      		'comp_id'		=> $id,
      		'comp_brand'	=> $computer->brand,
      		'comp_name'		=> $computer->name
      	));
      	View::make("review_add.html", array('review'=>$review));
	  } else {
        Redirect::to('/error', array('message' => 'Computer not found!'));
	  }
  	}

  	public static function do_review_add($id) {
		$review = self::reviewFromPOST();
  		$errors = $review->add();
    	if($errors) {
        	View::make("review_add.html", array('review'=>$review, 'error'=>$review->errors));
      	}
      	else {
        	Redirect::to('/computer_view/'.$_POST['comp_id'], array('message' => 'newReview'));
      	}
  	}


    /*
     *  Apumetoodi jolla saadaan arvostelu-objekti luotua helposti http POST-tiedoista.
     */
    public static function reviewFromPOST() {
      $review = new Review(array(
        'user_id'   => $_SESSION['userId'],
        'comp_id'  	=> $_POST['comp_id'],
        'comp_brand'=> $_POST['comp_brand'],
        'comp_name' => $_POST['comp_name'],
        'rating'    => $_POST['rating'],
        'review'    => $_POST['review'],
        'datum'     => date('d.m.Y')
      ));
      return $review;
    }

  }