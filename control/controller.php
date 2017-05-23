<?php

/*
 * File Name: controller.php
 * Author: Timothy Roush
 * Date Created: 5/18/17
 * Assignment: The Blogs Site
 * Description: Controller Component Of MVC Architecture
 */

 /**
  * Provides a separation of logic and output for the Blogs site.  Processes
  * and prepares all data required to produce each view in the routing document
  *
  * @author Timothy Roush
  * @copyright 2017
  * @version 1.0
  * @see DbOperator.php
  * @see index.php
  * @see Blogger.php
  * @see Blog.php
  */
class Controller {


    /**
     * Update the fat-free object to reflect the visitor's current member
     * status.  If the user is logged in, set a toggle and store Blogger data
     * @param $f3 fat-free instance to operate with
     */
    public function checkLogin($f3) {
        if ($_SESSION['user'] === true) {
            $f3->set('user', true);
            $f3->set('current', $_SESSION['current']);
        } else {
            $f3->set('user', false);
        }
    }


    /**
     * Controller functions to operate for when visitor wishes to log in to use
     * member-only features of the site.
     * @param $f3 fat-free instance to operate with
     */
    public function login($f3) {
        //Set environment tokens
        $f3->mset(array(
            'description' => 'Register New Blogger',
            'title' => 'Register',
        ));

        // If here by POST, see if user name exists
        if($_POST['action'] == 'login') {
            $operator = new DbOperator();
            $userName = $_POST['userName'];
            $password = $_POST['password'];
            
            // If user exists check password
            if ($operator->userExists($userName)) {
                $dbPassword = $operator->getPassword($userName);
                if ($this->verifyMatch(sha1($password), $dbPassword)) {
                    $_SESSION['user'] = true;
                    $id = $operator->getUserID($userName);
                    $_SESSION['current'] = $operator->getBlogger($id);
                    $f3->reroute('/');
                }
            }
        }
    }


    /**
     * Controller functions to operate for when visitor wishes to log out from
     * member-only features of the site.
     * @param $f3 fat-free instance to operate with
     */
    public function logout($f3) {
        session_destroy();
        $f3->set('user', false);
        $f3->clear('current');
    }


    /**
     * Controller functions to operate for when visitor views the main home
     * page of the site.  All Bloggers currently in the database are retrieved
     * and made avaialable to the template.
     * @param $f3 fat-free instance to operate with
     */
    public function home($f3) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'See What Our Bloggers Are Up To!',
            'title' => 'Welcome',
        ));
        
        // Retrieve all Bloggers from database
        $operator = new DbOperator();
        $bloggers = $operator->getAllBloggers();
        $f3->set('bloggers', $bloggers);
    }


    /**
     * Prepares necessary data to display the "about us" view
     * @param $f3 fat-free instance to operate with
     */
    public function aboutUs($f3) {
        //Set environemnt tokens
        $f3->mset(array(
            'description' => 'About Us',
            'title' => 'About Us'
        ));
    }


    /**
     * Prepares necessary data to display the "profile" view
     * @param $f3 fat-free instance to operate with
     * @param $id int record number of user to display a profile for
     */
    public function viewProfile($f3, $id) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'View A Profile',
            'title' => 'Profile View',
        ));
        
        // Get blogger data and create Blogger object
        $operator = new DbOperator();
        $blogger = $operator->getBlogger($id);

        // Make results available to view
        $f3->mset(array(
            'blogger' => $blogger,
            'blogs' => $operator->getBlogShortList($id),
            'lastBlog' => $blogger->getLastBlog()
        ));
    }


    /**
     * Controller functions to operate for when visitor to register as a member
     * and gain access to member-only features.  When visited via POST, data is
     * evaulated and processed to complete registration.
     * @param $f3 fat-free instance to operate with
     */
    public function register($f3) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'Register New Blogger',
            'title' => 'Register',
        ));
        
        // If POST data indicates 'create user'
        if (isset($_POST['action']) && $_POST['action'] == 'create') {
            $operator = new DbOperator();
            
            $id = -1;
            $userName = $_POST['userName'];
            $email = $_POST['email'];
            $password = sha1($_POST['password']);
            $image = $this->processFile($_FILES['image'], $userName);
            $bio = $_POST['bio'];
            
            $data = array(
                'id' => $id,
                'userName' => $userName,
                'email' => $email,
                'image' => $image,
                'bio' => $bio
            );
            
            // Create database entry and new Blogger instance
            $blogger = $this->createBlogger($data);
            $newID = $operator->addBlogger($blogger, $password);
            $blogger->setID($newID);
            
            // Store Blogger in SESSION and set toggle for logged in
            $_SESSION['current'] = $blogger;
            $_SESSION['user'] = true;
            
            $f3->reroute('/');
        }
    }


    /**
     * Prepares necessary data to display the "view blog" view
     * @param $f3 fat-free instance to operate with
     * @param $id int record number of blog to display
     */
    public function viewBlog($f3, $id) {
        $operator = new DbOperator();
        $blog = $operator->getBlog($id);
        $author = $operator->getBlogger($blog->getAuthor());
        $content = nl2br($blog->getContent());
        
        // Date formats
        $tempDate = strtotime($blog->getDateAdded());
        $dateAdded = date('F jS, Y', $tempDate);
        $tempDate = strtotime($blog->getDateEdited());
        $dateEdited = date('F jS, Y', $tempDate);
        
        // Make results available
        // TODO: Should just return a Blog object...
        $f3->mset(array(
            'description' => 'Viewing A Blog',
            'title' => $blog->getTitle(),
            'blogTitle' => $blog->getTitle(),
            'blogContent' => $content,
            'wordCount' => $blog->getWordCount(),
            'dateAdded' => $dateAdded,
            'dateEdited' => $dateEdited,
            'author' => $blog->getAuthor(),
            'authorName' => $author->getUserName(),
            'authorImage' => $author->getImage()
        ));
    }


    /**
     * Prepares necessary data to display the "my blogs" view
     * @param $f3 fat-free instance to operate with
     */
    public function myBlogs($f3) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'Manage Your Blogs',
            'title' => 'Blog Management',
            'fontAwesome' => true
        ));

        // Get necessary data
        $operator = new DbOperator();
        $blogger = $_SESSION['current'];
        $blogs = $operator->getBlogsList($blogger->getID());
        
        // Make results available to view
        $f3->mset(array(
            'blogs' => $blogs,
            'author' => $blogger->getUserName(),
            'bio' => $blogger->getBio(),
            'image' => $blogger->getImage()
        ));

    }


    /**
     * Prepares necessary data to process adding a blog
     * @param $f3 fat-free instance to operate with
     */
    public function addBlog($f3) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'Create A New Blog!',
            'title' => 'Blog Creation',
            'header' => 'What\'s on your mind?',
            'action' => './create',
            'submit' => 'create'
        ));
        
        // If here by valid POST, add blog
        if (isset($_POST['action']) && $_POST['action'] == 'create') {
            $operator = new DbOperator();

            // Store form data in a Blog object
            $data = array(
                'author' => $_SESSION['current']->getID(),
                'id' => -1,
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'wordCount' => str_word_count($_POST['content'])
            );
            $newBlog = $this->createBlog($data);
            
            // Signal operator to add blog to database and send user to my blogs
            $blogID = $operator->addBlog($newBlog);
            $f3->reroute('/myblogs');
        }
    }


    /**
     * Prepares necessary data to process editing a blog
     * @param $f3 fat-free instance to operate with
     * @param $id int record number of blog to edit
     */
    public function editBlog($f3, $id) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'Modify A Blog',
            'title' => 'Modify Blog',
            'header' => 'Change your mind?',
            'action' => './edit' . $id,
            'submit' => 'edit'
        ));
        
        $operator = new DbOperator();
        
        // If here by valid POST data, edit the blog
        if (isset($_POST['action']) && $_POST['action'] == 'edit') {
            $currentBlog = $_SESSION['currentBlog'];
            
            // Store form data in Blog object
            $data = array(
                'author' => $currentBlog->getAuthor(),
                'id' => $currentBlog->getID(),
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'wordCount' => str_word_count($_POST['content'])
            );
            $newBlog = $this->createBlog($data);
            
            // Signal operator to edit blog and send user to my blogs
            $operator->editBlog($newBlog);
            $f3->reroute('myblogs');
        } else {
            // Sticky form
            $_SESSION['currentBlog'] = $operator->getBlog($id);
            $blog = $_SESSION['currentBlog'];
            
            $f3->mset(array(
                'blogTitle' => $blog->getTitle(),
                'content' => $blog->getContent()
            ));
        }
    }


    /**
     * Prompts the Model object to remove the blogs database record matching
     * the specified id number.
     * @param $id int record number of blog to be removed
     */
    public function deleteBlog($f3, $id) {
        $operator = new DbOperator();
        $operator->deleteBlog($id);
    }


// METHODS - SUB-ROUTINES


    /**
     * Validates and prepares uploaded file for use in blogs site.  File is
     * checked for valid extension, then renamed with user name and moved to
     * designate profile photo folder.
     * @param $file Array data pertaining to uploaded file from $_FILES
     * @param $userName String user's name to rename file with
     * @return String final file name
     */
    private function processFile($file, $userName) {
        // Valid file extensions
        $validFiles = array('.gif','.png','.jpg','.jpeg');
        
        // Grab files extension and verify acceptable
        $fileExt = strstr($file['name'],".");
        if (in_array($fileExt, $validFiles)) {
            $fileName = $userName . $fileExt;
            if(move_uploaded_file($file['tmp_name'], 'images/profiles/' . $fileName)) {
                return $fileName;
            } else {
                return 'anon.png';
            }
        }
    }


    /**
     * Creates a new blogger object using the specified data array.  This
     * method expects a row of results from a database query.  The results must
     * include the fields: id, userName, email, image, and bio.  Data to
     * populate the Blogger object's lastBlog and blogCount fields are pulled
     * from the database using the supplied id value.
     * @param $data array row of results used to create Blogger
     * @return Blogger new Blogger object
     */
    private function createBlogger($data) {
        $operator = new DbOperator();
        
        $newBlogger = new Blogger($data['id'], $data['userName'], $data['email']);
        $newBlogger->setImage($data['image']);
        $newBlogger->setBio($data['bio']);
        //$newblogger->setBlogCount($operator->getBlogCount($data['id']));
        //$newblogger->setLastBlog($operator->getLastSummary($data['id']));
        
        return $newBlogger;
    }


    /**
     * Creates a new Blog object using the specified data array.  This
     * method expects a row of results from a database query.  The results must
     * include the fields: id, title, author, content, and wordcount.
     * @param $data array row of results used to create Blog
     * @return Blog new Blog object
     */
    private function createBlog($data) {
        $operator = new DbOperator();

        $newBlog= new Blog($data['id'], $data['title'], $data['author']);
        $newBlog->setContent($data['content']);
        $newBlog->setWordCount($data['wordCount']);

        return $newBlog;
    }


    /**
     * Helper method to determine if two supplied password values match. Values
     * should be hashed PRIOR to checking for equality.
     * @param $password String first value to compare
     * @param $match String second value to compare
     * @return boolean true if values match, false otherwise
     */
    private function verifyMatch($password, $match) {
        return $password === $match;
    }
}