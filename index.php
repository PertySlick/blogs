<?php
    // Routing Initialization
    require_once ('vendor/autoload.php');
    session_start();
    
    // Initiate fat-free and Controller objects
    $f3 = Base::instance();
    $f3->set('DEBUG', 3);
    $controller = new Controller();
    
    // Check if visitor is logged in
    $controller->checkLogin($f3);

    // Default route home page for arriving visitors or those that logout
    $f3->route('GET /', function($f3) use ($controller) {
        $controller->home($f3);
        echo \Template::instance()->render('view/home.html');
      });
    
    // User wants to register and gain access to member features
    $f3->route('GET|POST /register', function($f3) use ($controller) {
        $controller->register($f3);
        echo \Template::instance()->render('view/register.html');
    });
    
    // User wants to create a new Blog
    $f3->route('GET|POST /create', function($f3) use ($controller) {
        $controller->addBlog($f3);
        echo \Template::instance()->render('view/blogedit.html');
    });
    
    // User wants to edit their blog
    $f3->route('GET|POST /edit@id', function($f3, $params) use ($controller) {
        $controller->editBlog($f3, $params['id']);
        echo \Template::instance()->render('view/blogedit.html');
    });
    
    // User wants to view a blog
    $f3->route('GET /@id', function($f3,$params) use ($controller) {
       $controller->viewBlog($f3, $params['id']);
       echo \Template::instance()->render('view/viewblog.html');
    });
    
    // User wants to login and access registered member features
    $f3->route('GET|POST /login', function($f3) use ($controller) {
        $controller->login($f3);
        echo \Template::instance()->render('view/login.html');
    });
    
    // User wants to logout and not have access to member features
    $f3->route('GET /logout', function($f3) use ($controller) {
        $controller->logout($f3);
    });
    
    // Execute route
    $f3->run();