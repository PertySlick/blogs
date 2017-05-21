<?php


class Controller {
    
    
    public function register($f3) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'Register New Blogger',
            'title' => 'Register',
            
            // Temporary Objects TODO: REMOVE
            //'user' => true,
            //'current' => $this->makeBlogger()
        ));
        
        // If POST data,
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
            
            $blogger = $this->createBlogger($data);

            $newID = $operator->addBlogger($blogger, $password);
            $blogger->setID($newID);
            
            $_SESSION['current'] = $blogger;
            $_SESSION['user'] = true;
            
            $f3->reroute('/');
        }
    }
    
    // Validates and moves uploaded file.  Returns file name
    private function processFile($file, $userName) {
        // Valid file extensions
        $validFiles = array('.gif','.png','.jpg','.jpeg');
        
        // Grab files extension and verify acceptable
        $fileExt = strstr($file['name'],".");
        if (in_array($fileExt, $validFiles)) {
            $fileName = $userName . $fileExt;
            if(move_uploaded_file($file['tmp_name'], 'images/' . $fileName)) {
                return $fileName;
            } else {
                return 'anon.png';
            }
        }
    }
    
    // Ensures password and verfiy value match
    private function verifyMatch($password, $verify) {
        
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
}