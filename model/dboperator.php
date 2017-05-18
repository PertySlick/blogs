<?php

class DbOperator {


// FIELDS AND OBJECTS


    private $_conn;                             // Database Connection Object


// CONSTRUCTOR


    public __construct() {
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


    public addBlogger($blogger) {
        $stmt = $this->_conn->prepare(
            'INSERT INTO bloggers
            (firstName, lastName, image, bio)
            VALUES (
            $blogger->getFirstName(),
            $blogger->getLastName(),
            $blogger->getImage(),
            $blogger->getBio())'
            );
    }
}