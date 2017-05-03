<?php

    require_once ('vendor/autoload.php');
    session_start();
    
    $f3 = Base::instance();
    $f3->set('DEBUG', 3);
    
    $f3->route('GET /', function($f3) {                     // Default Route
        echo \Template::instance()->render('view/home.html');
      });
    
    $f3->run();