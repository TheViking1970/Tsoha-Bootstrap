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

  $routes->get('/computer_view', function() {
    VirtualMuseumController::computer_view();
  });

  $routes->get('/computer_edit', function() {
    VirtualMuseumController::computer_edit();
  });

  $routes->get('/users_list', function() {
    VirtualMuseumController::users_list();
  });

  $routes->get('/user_view', function() {
    VirtualMuseumController::user_view();
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

  $routes->get('/logout', function() {
    VirtualMuseumController::logout();
  });

  $routes->get('/profile', function() {
    VirtualMuseumController::profile();
  });

  $routes->get('/profile_edit', function() {
    VirtualMuseumController::profile_edit();
  });


  $routes->get('/hiekkalaatikko', function() {
    VirtualMuseumController::sandbox();
  });
