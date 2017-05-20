<?php

class DbOperator
{


// FIELDS AND OBJECTS

    private const SUMMARY_LENGTH = 250;     // Amount of text in blog summary
    private $_conn;                         // Database Connection Object


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


// METHODS - BLOGGER INFORMATION


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
            'SELECT id, userName, email, image, bio' .
            'FROM bloggers ' .
            'WHERE id=:id'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Execute PDO Statement
        $stmt->execute();
        $results = $stmt->fetch( PDO::FETCH_ASSOC );
        
        $blogger = new Blogger($results['userName'], $results['email']);
        $blogger->setImage($results['image']);
        $blogger->setBio($results['bio']);
        $blogger->setBlogCount($this->getBlogCount($id));
        $blogger->setLastBlog($this->getLastSummary($id));
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
        $stmt = 'SELECT id FROM bloggers';
        $results = $_conn->query($stmt);
        
        // Create a Blogger object for each id and store in array
        if ($results->rowCount() > 0) {
            foreach ($results as $result) {
                $bloggers[] = getBlogger($result['id']);
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
    public function addBlogger($blogger)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'INSERT INTO bloggers ' .
            '(userName, email, image, bio)' .
            'VALUES ' .
            '(:userName, :email, :image, :bio)'
            );
        
        // Bind Statement Parameters
        $stmt->bindParam(':userName', $blogger->getUserName(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $blogger->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':image', $blogger->getImage(), PDO::PARAM_STR);
        $stmt->bindParam(':bio', $blogger->getBio(), PDO::PARAM_STR);
        
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


// METHODS - SUB-ROUTINES


    private function emailExists($email)
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


    private function userExists($userName)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'SELECT COUNT(*) AS count' .
            'FROM bloggers' .
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


    private function idExists($id)
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


    private function getBlogCount($id) {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare(
            'SELECT COUNT() as count ' .
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


    private function getLastSummary($id) {
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
        
        if ($results->rowCount() > 0) {
            return substr($results['content'], 0, SUMMARY_LENGTH);
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
        $newblogger = new Blogger($data['userName'], $data['email']);
        $newblogger->setImage($data['image']);
        $newblogger->setBio($data['bio']);
        $newblogger->setBlogCount($this->getBlogCount($data['id']));
        $newblogger->setLastBlog($this->getLastSummary($data['id']));
    }
}