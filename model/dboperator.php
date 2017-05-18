<?php

class DbOperator
{


// FIELDS AND OBJECTS


    private $_conn;                             // Database Connection Object


// CONSTRUCTOR


    public __construct()
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
    public addBlogger($blogger)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare('
            INSERT INTO bloggers
                (firstName, lastName, image, bio)
            VALUES
                (:fName, :lName, :image, :bio)
            ');
        
        // Bind Statement Parameters
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


    public modifyBlogger($blogger)
    {
        // Create Prepared Statement
        $stmt = $this->_conn->prepare('
            UPDATE bloggers
            SET
                firstName=:fname,
                lastName=:lname,
                image=:image,
                bio=:bio,
            WHERE id=:id
            ');
        
        // Bind Statement Parameters
        $stmt->bindParam(':fname', $blogger->getFirstName(), PDO::PARAM_STR);
        $stmt->bindPAram(':lname', $blogger->getFirstNameO(), PDO::PARAM_STR);
        $stmt->bindParam(':fname', $blogger->getFirstName(), PDO::PARAM_STR);
        $stmt->bindParam(':fname', $blogger->getFirstName(), PDO::PARAM_STR);
        
        // Execute PDO Statement
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die( '(!) Error Updating Profile: ' . $e->getMessage());
        }
    }
}