<?php
    // Routing Initialization
    require_once ('vendor/autoload.php');
    session_start();
    
    $controller = new Controller();
    
    $f3 = Base::instance();
    $f3->set('DEBUG', 3);
    
    

    $f3->route('GET /', function($f3) {                     // Default Route
        $operator = new DbOperator();
        
        $bloggers = $operator->getAllBloggers();
        
        $f3->set('bloggers', $bloggers);
       
        echo \Template::instance()->render('view/home.html');
      });
    
    $f3->route('GET|POST /register', function($f3) use ($controller) {
        $controller->register($f3);
        echo \Template::instance()->render('view/register.html');
    });
    
    $f3->run();