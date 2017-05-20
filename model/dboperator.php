<?php

class DbOperator
{


// FIELDS AND OBJECTS


    private $_conn;                             // Database Connection Object


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
        $stmt = $this->_conn->prepare('
            INSERT INTO bloggers
                (userName, email, firstName, lastName, image, bio)
            VALUES
                (:userName, :email, :fName, :lName, :image, :bio)
            ');
        
        // Bind Statement Parameters
        $stmt->bindParam(':userName', $blogger->getUserName(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $blogger->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':fName', $blogger->getFirstName(), PDO::PARAM_STR);
        $stmt->bindParam(':lName', $blogger->getLastName(), PDO::PARAM_STR);
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
        $stmt = $this->_conn->prepare('
            UPDATE bloggers
            SET
                userName=:userName,
                email=:email,
                firstName=:fname,
                lastName=:lname,
                image=:image,
                bio=:bio
            WHERE id=:id
            ');
        
        // Bind Statement Parameters
        $stmt->bindParam(':userName', $blogger->getUserName(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $blogger->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':fname', $blogger->getFirstName(), PDO::PARAM_STR);
        $stmt->bindPAram(':lname', $blogger->getLastNameO(), PDO::PARAM_STR);
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


    private function emailExists($email)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare('
            SELECT
                COUNT(*) AS count
            FROM bloggers
            WHERE email=:email
            ');
        
        // Bind Statement Parameters
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        // Execute PDO Statement
        $results = $stmt->execute();
        
        // Return results
        if ($results['count'] > 0) return true;
        else return false;
    }


    private function userExists($userName)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare('
            SELECT
                COUNT(*) AS count
            FROM bloggers
            WHERE userName=:user
            ');
        
        // Bind Statement Parameters
        $stmt->bindParam(':user', $userName, PDO::PARAM_STR);
        
        // Execute PDO Statement
        $results = $stmt->execute();
        
        // Return results
        if ($results['count'] > 0) return true;
        else return false;
    }
}