<?php
    // Routing Initialization
    require_once ('vendor/autoload.php');
    session_start();
    
    // Initiate fat-free, and Controller objects and set defaults
    $f3 = Base::instance();                 // Instance of Fat Free object
    $f3->set('DEBUG', 3);                   // Set Fat Free debug level
    $controller = new Controller();         // MVC Controller object
    $f3->set('fontAwesome', false);         // Should include load fontAwesome?
    
    // Check if visitor is logged in
    $controller->checkLogin($f3);

    // Default route home page for arriving visitors or those that logout
    $f3->route('GET /', function($f3) use ($controller) {
        $controller->home($f3);
        echo \Template::instance()->render('view/home.html');
      });
    
    //User wants to learn more about the site with "About Us"
    $f3->route('GET /about', function($f3) use ($controller) {
        $controller->aboutUs($f3);
        echo \Template::instance()->render('view/about.html');
    });
    
    // User wants to register and gain access to member features
    $f3->route('GET|POST /register', function($f3) use ($controller) {
        $controller->register($f3);
        echo \Template::instance()->render('view/register.html');
    });
    
    // USer wants to view another user's profile
    $f3->route('GET /profile@id', function($f3, $params) use ($controller) {
        $controller->viewProfile($f3, $params['id']);
        echo \Template::instance()->render('view/profile.html');
    });
    
    // User wants to view and manage their blogs
    $f3->route('GET /myblogs', function($f3) use ($controller) {
        $controller->myBlogs($f3);
        echo \Template::instance()->render('view/myblogs.html');
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
    $f3->route('GET|POST /@id', function($f3,$params) use ($controller) {
       $controller->viewBlog($f3, $params['id']);
       echo \Template::instance()->render('view/viewblog.html');
    });
    
    // User wants to delete a blog
    $f3->route('GET /delete@id', function($f3, $params) use ($controller) {
        $controller->deleteBlog($f3, $params['id']);
        $f3->reroute('/myblogs');
    });
    
    // User wants to login and access registered member features
    $f3->route('GET|POST /login', function($f3) use ($controller) {
        $controller->login($f3);
        echo \Template::instance()->render('view/login.html');
    });
    
    // User wants to logout and not have access to member features
    $f3->route('GET /logout', function($f3) use ($controller) {
        $controller->logout($f3);
        $f3->reroute('/');
    });
    
    // Execute route
    $f3->run();