<?php

  $routes->get('/', function() {
    VirtualMuseumController::home();
  });

  $routes->get('/index', function() {
    VirtualMuseumController::home();
  });

  $routes->get('/computers_list', function() {
    ComputersController::computers_list();
  });

  $routes->get('/computer_view/:id', function($id) {
    ComputersController::computer_view($id);
  });

  $routes->get('/computer_edit/:id', function($id) {
    ComputersController::computer_edit($id);
  });

  $routes->get('/computer_add', function() {
    ComputersController::computer_add();
  });

  $routes->post('/do_computer_add', function() {
    ComputersController::do_computer_add();
  });

  $routes->post('/do_computer_edit/:id', function($id) {
    ComputersController::do_computer_edit($id);
  });

  $routes->get('/computer_delete/:id', function($id) {
    ComputersController::computer_delete($id);
  });

  $routes->get('/users_list', function() {
    UsersController::users_list();
  });

  $routes->get('/user_view/:id', function($id) {
    UsersController::user_view($id);
  });

  $routes->get('/review_add/:id', function($id) {
    ReviewsController::review_add($id);
  });

  $routes->get('/register', function() {
    UsersController::register();
  });

  $routes->post('/register', function() {
    UsersController::do_register();
  });

  $routes->get('/login', function() {
    UsersController::login();
  });

  $routes->post('/login', function() {
    UsersController::do_login();
  });

  $routes->get('/logout', function() {
    UsersController::logout();
  });

  $routes->get('/profile', function() {
    UsersController::profile();
  });

  $routes->get('/profile_edit', function() {
    UsersController::profile_edit();
  });

  $routes->post('/do_profile_edit', function() {
    UsersController::do_profile_edit();
  });

  $routes->get('/thank_you', function() {
    VirtualMuseumController::thank_you();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
