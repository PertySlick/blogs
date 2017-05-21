<?php
    // Routing Initialization
    require_once ('vendor/autoload.php');
    session_start();
    
    $controller = new Controller();
    
    $f3 = Base::instance();
    $f3->set('DEBUG', 3);
    
    // Check if visitor is logged in
    $controller->checkLogin($f3);

    $f3->route('GET /', function($f3) use ($controller) {                     // Default Route
        $controller->home($f3);
        echo \Template::instance()->render('view/home.html');
      });
    
    $f3->route('GET|POST /register', function($f3) use ($controller) {
        $controller->register($f3);
        echo \Template::instance()->render('view/register.html');
    });
    
    $f3-route('GET /logout', function($f3) use ($controller) {
        
    })
    
    $f3->run();