<?php

/*
 * File Name: dboperator.php
 * Author: Timothy Roush
 * Date Created: 5/13/17
 * Assignment: The Blogs Site
 * Description:  Model MVC Component - Handles Database Operations
 */

 /**
  * DbOperator represents an instance of a "model" component of the MVC style
  * architecture.  This class handles any and all database interaction
  * operations at the request of the controller component.  Class employs PDO
  * SQL queries and statements to sanitize inputs as they are entered into the
  * database.  Operator designed to always be passing either system determined
  * integer values or Blogger/Blog objects to and from methods where ever
  * possible.  This operator will only work with the Blogger and Blog classes.
  *
  * @author Timothy Roush
  * @copyright 2017
  * @version 1.0
  * @see Blogger.php
  * @see Blog.php
  */
class DbOperator
{


// FIELDS - CONSTANTS AND OBJECTS

    const SUMMARY_LENGTH = 250;     // Amount of text in blog summary
    private $_conn;                 // Database Connection Object


// CONSTRUCTOR


    /**
     * Creates an instance of a database interaction object.  Requires access
     * to an externally stored credentials file for accessing the database.
     * @throws PDOException if error encountered while establishing connection
     */
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
    public function getBlogger($id)
    {
        // Create prepared statement
        $stmt = $this->_conn->prepare(
            'SELECT id, userName, email, image, bio ' .
            'FROM bloggers ' .
            'WHERE id=:id'
            );
        
        // Bind statement parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Execute PDO statement and return results
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetch( PDO::FETCH_ASSOC );
            $blogger = $this->createBlogger($results);
            // Return a Blogger object
            return $blogger;
        } else {
            die('<strong>(!)</strong> There was an error retrieving Blogger with ID# ' . $id);
        }
    }


    /**
     * Retrieves the id for the specified user if they exist in the database.
     * @param $userName String user name to search database for
     * @return int blogger's database id number, or -1 for not found
     */
    public function getUserID($userName)        // Replaces userExists()?
    {      
        // Prepare PDO statement
        $stmt = $this->_conn->prepare(
            'SELECT id ' .
            'FROM bloggers ' .
            'WHERE userName=:userName'
        );
        
        // Bind parameters and get results
        $stmt->bindParam(':userName', $userName);
        $stmt->execute();
        
        // Return results if any, -1 otherwise
        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            return $results['id'];              // Return id number retrieved
        } else {
            return -1;
        }
        
        
        
    }


    /**
     * Retrieves the value stored as the specified user's password from the
     * database.  This method is meant strictly for login credential
     * verification.
     * @param $userName String user name to search database for
     * @return String value stored as user's password
     */
    public function getPassword($userName)
    {
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
    public function getAllBloggers()
    {
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


    /**
     * Retrieves all data stored for the specified record number blog and
     * returns it in a Blog object.
     * @param $id int database record number to retrieve from blogs
     * @return Blog object storing all daa for specified blog
     */
    public function getBlog($id)
    {
        // Prepare PDO statement
        $stmt = $this->_conn->prepare(
            'SELECT * ' .
            'FROM blogs ' .
            'WHERE id=' . $id
        );
        
        //Get Results and return them as a Blog
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->createBlog($data);
    }
    
    
    /**
     * Retrieves a list of an author's blogs storing just the id and title
     * @param $id int id of author to find blogs for
     * @return Array all records return for author
     */
    public function getBlogsList($id)
    {
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


    /**
     * Retrieves all blog data for the specified author id.  Content stored
     * in results is shortened to the value of SUMMARY_LENGTH for summary.
     * Data is returned as an array of Blog objects.
     * @param $author int id of author to pull blogs for
     * @return Array of Blog objects
     */
    public function getBlogShortList($author)
    {
        $results = array();                         // Initialize result array
        $blogList = $this->getBlogsList($author);   // Get a list of blog IDs

        // Get full blog data for each blog ID and package in array
        foreach ($blogList as $blog) {
            $tempBlog = $this->getBlog($blog['id']);
            $tempBlog->setContent(substr($tempBlog->getContent(), 0, $this::SUMMARY_LENGTH));
            $results[] = $tempBlog;
        }
        
        // Return array of Blogs
        return $results;
    }


    /**
     * Add specified Blog object data to database as a new blog entry.
     * @param $blog Blog object storing values to add to database
     */
    public function addBlog($blog)
    {
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


    /**
     * Modifies an existing blog entry in the database with the values stored
     * in the specified Blog object.
     * @param $blog Blog object storing values to update blog entry
     */
    public function editBlog($blog)
    {
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


    /**
     * Removes the entire specified blog entry from the database.
     * @param $id int record number to be removed
     */
    public function deleteBlog($id)
    {
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


    /**
     * Determines if the supplied email exists in the bloggers database table
     * @param $email String email to check for
     * @return true if email is found, false otherwise
     */
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


    /**
     * Determines if specified user exists in the bloggers database table
     * @param $userName String user name to locate in database
     * @return true if user name exists, false otherwise
     */
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


    /**
     * Determines if specified user ID is in the bloggers database table
     * @param $id int user record number to locate in database
     * @return true if user ID exists, false otherwise
     */
    public function idExists($id)
    {
        // Prepare PDO statement
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


    /**
     * Returns the number of blogs the specified author has stored in database
     * @param $id int author record number to find blogs for
     * @return int number of blogs associated with author ID
     */
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


    /**
     * Retrieves the summarized content of the last blog written by the
     * specified author.  Blog content is summarized to the value of
     * SUMMARY_LENGTH.
     * @param $id int author record number to locate a blog for
     * @return String summarized string of blog contents
     */
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


    /**
     * Creates a Blog object for returning data to Controller for use.  Blog
     * object created using Blog class methods.
     * @param $data Array data to use in creating a Blog object
     * @return Blog object
     */
    private function createBlog($data) {
        // Create Blog object
        $newBlog= new Blog($data['id'], $data['title'], $data['author']);
        $newBlog->setContent($data['content']);
        $newBlog->setWordCount($data['wordCount']);
        $newBlog->setDateAdded($data['dateAdded']);
        $newBlog->setDateEdited($data['dateEdited']);
        
        // Return new Blog object
        return $newBlog;
    }
}