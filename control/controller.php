<?php


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
                } else {
                    echo 'NOOOOOOOOOOOOOOOOO!'; // TODO: FINISH
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
    
    
    public function myBlogs($f3) {
        $f3->mset(array(
            'description' => 'Manage Your Blogs',
            'title' => 'Blog Management',
            'fontAwesome' => true
        ));
                
        $operator = new DbOperator();
        $blogger = $_SESSION['current'];
        
        $blogs = $operator->getBlogsList($blogger->getID());
        $f3->mset(array(
            'blogs' => $blogs,
            'author' => $blogger->getUserName(),
            'bio' => $blogger->getBio(),
            'image' => $blogger->getImage()
        ));

    }


    public function addBlog($f3) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'Create A New Blog!',
            'title' => 'Blog Creation',
            'header' => 'What\'s on your mind?',
            'action' => './create',
            'submit' => 'create'
        ));
        
        if (isset($_POST['action']) && $_POST['action'] == 'create') {
            $operator = new DbOperator();

            $data = array(
                'author' => $_SESSION['current']->getID(),
                'id' => -1,
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'wordCount' => str_word_count($_POST['content'])
            );
            $newBlog = $this->createBlog($data);
            $blogID = $operator->addBlog($newBlog);
            $f3->reroute('/myblogs');
        }
    }
    
    
    public function editBlog($f3, $id) {
        //TODO: Check if blog id exists
        $f3->mset(array(
            'description' => 'Modify A Blog',
            'title' => 'Modify Blog',
            'header' => 'Change your mind?',
            'action' => './edit' . $id, //$_SESSION['currentBlog']->getID(),
            'submit' => 'edit'
        ));
        
        $operator = new DbOperator();
        
        if (isset($_POST['action']) && $_POST['action'] == 'edit') {
            $currentBlog = $_SESSION['currentBlog'];
            
            $data = array(
                'author' => $currentBlog->getAuthor(),
                'id' => $currentBlog->getID(),
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'wordCount' => str_word_count($_POST['content'])
            );
            
            $newBlog = $this->createBlog($data);
            $operator->editBlog($newBlog);
            $f3->reroute('myblogs');
        } else {
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
    
    // Validates and moves uploaded file.  Returns file name
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
        echo $data['email'] . "<br />";
        echo 'Blogger MADE';
        $newBlogger->setImage($data['image']);
        $newBlogger->setBio($data['bio']);
        //$newblogger->setBlogCount($operator->getBlogCount($data['id']));
        //$newblogger->setLastBlog($operator->getLastSummary($data['id']));
        
        return $newBlogger;
    }


    // Creates a Blog object for sending to DbOperator for database entry
    private function createBlog($data) {
        $operator = new DbOperator();
        
        $newBlog= new Blog($data['id'], $data['title'], $data['author']);
        $newBlog->setContent($data['content']);
        $newBlog->setWordCount($data['wordCount']);
        
        return $newBlog;
    }


// METHODS - VALIDATION


    private function verifyMatch($password, $match) {
        return $password === $match;
    }
}