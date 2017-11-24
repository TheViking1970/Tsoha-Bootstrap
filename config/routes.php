<?php

  $routes->get('/', function() {
    VirtualMuseumController::home();
  });

  $routes->get('/index', function() {
    VirtualMuseumController::home();
  });

  $routes->get('/computers_list', function() {
    VirtualMuseumController::computers_list();
  });

  $routes->get('/computer_view/:id', function($id) {
    VirtualMuseumController::computer_view($id);
  });

  $routes->get('/computer_edit/:id', function($id) {
    VirtualMuseumController::computer_edit($id);
  });

  $routes->get('/computer_add', function() {
    VirtualMuseumController::computer_add();
  });

  $routes->post('/do_computer_add', function() {
    VirtualMuseumController::do_computer_add();
  });

  $routes->post('/do_computer_edit/:id', function($id) {
    VirtualMuseumController::do_computer_edit($id);
  });

  $routes->get('/computer_delete/:id', function($id) {
    VirtualMuseumController::computer_delete($id);
  });

  $routes->get('/users_list', function() {
    VirtualMuseumController::users_list();
  });

  $routes->get('/user_view/:id', function($id) {
    VirtualMuseumController::user_view($id);
  });

  $routes->get('/review_add', function() {
    VirtualMuseumController::review_add();
  });

  $routes->get('/register', function() {
    VirtualMuseumController::register();
  });

  $routes->get('/login', function() {
    VirtualMuseumController::login();
  });

  $routes->post('/login', function() {
    VirtualMuseumController::do_login();
  });

  $routes->get('/logout', function() {
    VirtualMuseumController::logout();
  });

  $routes->get('/profile', function() {
    VirtualMuseumController::profile();
  });

  $routes->get('/profile_edit', function() {
    VirtualMuseumController::profile_edit();
  });

  $routes->get('/thank_you', function() {
    VirtualMuseumController::thank_you();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
