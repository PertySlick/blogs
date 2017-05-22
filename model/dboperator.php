<?php

class DbOperator
{


// FIELDS AND OBJECTS

    const SUMMARY_LENGTH = 250;     // Amount of text in blog summary
    private $_conn;                 // Database Connection Object


// CONSTRUCTOR


    public function __construct()
    {
        // Require Configuration File
        require_once '/home/troush/secure/credentials_blogs.inc.php';
        
        // Establish Database Connection And Set Attributes
        try {
            $this->_conn = new PDO( DB_DSN, DB_USER, DB_PASSWORD );
            $this->_conn->setAttribute( PDO::ATTR_PERSISTENT, true );
            $this->_conn->setAttribute(
                PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
            );
        } catch ( PDOException $e ) {
            die( "(!) Error - Connection Failed: " . $e->getMessage() );
        }
    }


// METHODS - BLOGGER OPERATIONS


    /**
     * Retrieves data from the database related to the provided id number.
     * Data is used to compile a Blogger object which is then returned.  If the
     * provided id does not exist in the database, a null value is returned.
     * @param $id int id number to retrieve
     * @return Blogger object of results, null if not found
     */
    public function getBlogger($id) {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'SELECT id, userName, email, image, bio ' .
            'FROM bloggers ' .
            'WHERE id=:id'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Execute PDO Statement
        $stmt->execute();
        $results = $stmt->fetch( PDO::FETCH_ASSOC );

        $blogger = $this->createBlogger($results);
        
        return $blogger;
    }


    /**
     * Retrieves the id for the specified user if they exist in the database.
     * @param $userName String user name to search database for
     * @return int blogger's database id number
     */
    public function getUserID($userName) {      // TODO: Maybe if null replaces userExists()?
        // Prepare PDO statement
        $stmt = $this->_conn->prepare(
            'SELECT id ' .
            'FROM bloggers ' .
            'WHERE userName=:userName'
        );
        
        // Bind parameters and get results
        $stmt->bindParam(':userName', $userName);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return id number retrieved
        return $results['id'];
    }


    /**
     * Retrieves the value stored as the specified user's password from the
     * database.  This method is meant strictly for login credential
     * verification.
     * @param $userName String user name to search database for
     * @return String value stored as user's password
     */
    public function getPassword($userName) {
        // Prepare PDO statement
        $stmt = $this->_conn->prepare(
            'SELECT password ' .
            'FROM bloggers ' .
            'WHERE userName=:userName'
        );
        
        // Bind parameters and get results
        $stmt->bindParam(':userName', $userName);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return password value retrieved
        return $results['password'];
    }


    /**
     * Retrieves all data for all bloggers currently registered.  Data is used
     * to build Blogger objects which are then stored in an array.  This array
     * is then returned at the end of the method.  If no bloggers are found in
     * the database, a null value is returned.
     * @return array() array of Blogger objects, null if none exist
     */
    public function getAllBloggers() {
        // Array to store Blogger objects to return
        $bloggers = array();
        
        // Query database for blogger id numbers
        $stmt = $this->_conn->prepare(
            'SELECT id ' .
            'FROM bloggers '
            );
        $stmt->execute();
        
        // Create a Blogger object for each id and store in array
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bloggers[] = $this->getBlogger($row['id']);
            }
            return $bloggers;                   // Return array of bloggers
        } else {
            return null;                        // Return null if no bloggers
        }
    }


    /**
     * Add a User/Blogger
     *
     * Adds a Blogger to the database using information stored in a Blogger
     * object.  The member_id of the blogger is returned for use in parent
     * block.
     * @param $blogger Blogger object storing user details
     * @return int database row id of added blogger data
     */
    public function addBlogger($blogger, $password)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'INSERT INTO bloggers ' .
            '(userName, email, image, bio, password)' .
            'VALUES ' .
            '(:userName, :email, :image, :bio, :password)'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':userName', $blogger->getUserName(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $blogger->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':image', $blogger->getImage(), PDO::PARAM_STR);
        $stmt->bindParam(':bio', $blogger->getBio(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        
        // Execute PDO Statement
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die('(!) Error Adding Blogger: ' . $e->getMessage());
        }
        
        // Return User/Blogger ID
        return $this->_conn->lastInsertId();
    }


    /**
     * Updates the database record matching the Blogger object supplied.
     * Blogger first name, last name, image, and bio are updated with the
     * values stored in side the object.  Database record found using the id
     * value stored in the Blogger object.
     * @param $blogger Blogger object with values
     */
    public function modifyBlogger($blogger)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'UPDATE bloggers' .
            'SET' .
                'userName=:userName,' .
                'email=:email,' .
                'image=:image,' .
                'bio=:bio' .
            'WHERE id=:id'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':userName', $blogger->getUserName(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $blogger->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':image', $blogger->getImage(), PDO::PARAM_STR);
        $stmt->bindParam(':bio', $blogger->getBio(), PDO::PARAM_STR);
        $stmt->bindParam(':id', $blogger->getID(), PDO::PARAM_INT);
        
        // Execute PDO Statement
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die( '(!) Error Updating Profile: ' . $e->getMessage());
        }
    }


// METHODS - BLOG OPERATIONS


    public function getBlog($id) {
        // Prepare PDO statement
        $stmt = $this->_conn->prepare(
            'SELECT * ' .
            'FROM blogs ' .
            'WHERE id=' . $id
        );
        
        //Get Results
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $this->createBlog($data);
    }
    
    
    public function getBlogsList($id) {
        $stmt = $this->_conn->prepare(
            'SELECT id, title ' .
            'FROM blogs ' .
            'WHERE author=:id ' .
            'ORDER BY id DESC'
        );
        
        // Bind parameters and get results
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getBlogShortList($author) {
        $temp = array();
        $results = array();
        $blogList = $this->getBlogsList($author);

        foreach ($blogList as $blog) {
            $tempBlog = $this->getBlog($blog['id']);
            $tempBlog->setContent(substr($tempBlog->getContent(), 0, $this::SUMMARY_LENGTH));
            $results[] = $tempBlog;
        }
        
        return $results;
    }


    public function addBlog($blog) {
        // Prepare PDO statement
        $stmt = $this->_conn->prepare(
            'INSERT INTO blogs ' .
            '(author, title, content, wordCount, dateAdded) ' .
            'VALUES (:author, :title, :content, :wordCount, NOW())'
        );
        
        // Bind Parameters and execute
        $stmt->bindParam(':author', $blog->getAuthor(), PDO::PARAM_INT);
        $stmt->bindParam(':title', $blog->getTitle(), PDO::PARAM_STR);
        $stmt->bindParam(':content', $blog->getContent(), PDO::PARAM_STR);
        $stmt->bindParam(':wordCount', $blog->getWordCount(), PDO::PARAM_INT);
        $stmt->execute();
    }
    
    
    public function editBlog($blog) {
        // Prepare PDO statement
        $stmt = $this->_conn->prepare(
            'UPDATE blogs ' .
            'SET title=:title,' .
            'content=:content,' .
            'wordCount=:wordCount ' .
            'WHERE id=:id'
        );
        
        // Bind Parameters and execute
        $stmt->bindParam(':title', $blog->getTitle(), PDO::PARAM_STR);
        $stmt->bindParam(':content', $blog->getContent(), PDO::PARAM_STR);
        $stmt->bindParam(':wordCount', $blog->getWordCount(), PDO::PARAM_INT);
        $stmt->bindParam(':id', $blog->getID(), PDO::PARAM_INT);
        $stmt->execute();
    }
    
    
    public function deleteBlog($id) {
        // Prepare PDO statement
        $stmt = $this->_conn->prepare(
            'DELETE FROM blogs ' .
            'WHERE id=:id'
        );
        
        // Bind parameters and execute
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }


// METHODS - SUB-ROUTINES


    public function emailExists($email)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'SELECT COUNT(*) AS count' .
            'FROM bloggers' .
            'WHERE email=:email'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        // Execute PDO Statement
        $stmt->execute();
        $results = $stmt->fetch( PDO::FETCH_ASSOC );
        
        // Return results
        if ($results['count'] > 0) return true;
        else return false;
    }


    public function userExists($userName)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'SELECT COUNT(*) AS count ' .
            'FROM bloggers ' .
            'WHERE userName=:user'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':user', $userName, PDO::PARAM_STR);
        
        // Execute PDO Statement
        $stmt->execute();
        $results = $stmt->fetch( PDO::FETCH_ASSOC );
        
        // Return results
        if ($results['count'] > 0) return true;
        else return false;
    }


    public function idExists($id)
    {
        
        $stmt = $this->_conn->prepare(
            'SELECT COUNT(*) AS count' .
            'FROM bloggers' .
            'WHERE id=:id'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Execute PDO Statement
        $stmt->execute();
        $results = $stmt->fetch( PDO::FETCH_ASSOC );
        
        // Return results
        if ($results['count'] > 0) return true;
        else return false;
    }


    public function getBlogCount($id) {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'SELECT COUNT(*) as count ' .
            'FROM blogs ' .
            'WHERE author=:id'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Return results
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $results['count'];
    }


    public function getLastSummary($id) {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'SELECT content ' .
            'FROM blogs ' .
            'WHERE author=:id ' .
            'ORDER BY dateAdded DESC ' .
            'LIMIT 1'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Return results
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $temp = $results['content'];
        if ($stmt->rowCount() > 0) {
            return substr($temp, 0, $this::SUMMARY_LENGTH);
        } else {
            return 'User has not submitted a blog just yet...';
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
        $newBlogger = new Blogger($data['id'], $data['userName'], $data['email']);
        $newBlogger->setImage($data['image']);
        $newBlogger->setBio($data['bio']);
        $newBlogger->setBlogCount($this->getBlogCount($data['id']));
        $newBlogger->setLastBlog($this->getLastSummary($data['id']));
        
        return $newBlogger;
    }


    // Creates a Blog object for sending to Controller for use
    private function createBlog($data) {
        $newBlog= new Blog($data['id'], $data['title'], $data['author']);
        $newBlog->setContent($data['content']);
        $newBlog->setWordCount($data['wordCount']);
        $newBlog->setDateAdded($data['dateAdded']);
        $newBlog->setDateEdited($data['dateEdited']);
        
        return $newBlog;
    }
}