<?php

class Blogger {
    const DEFAULT_IMAGE = "male.png";

    private $_firstName;
    private $_lastName;
    private $_blogCount;
    private $_image;
    private $_lastBlog;

    function __construct($firstName, $lastName) {
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
    }


// METHODS - GETTERS


    /**
     * Retrieves the value stored as this blogger's first name.
     * @return String blogger's first name
     */
    function getFirstName() {
        return $this->_firstName;
    }


    /**
     * Retrieves the value stored as this blogger's last name.
     * @return String blogger's last name
     */
    function getLastName() {
        return $this->_lastName;
    }


    /**
     * Retrieves the full name stored for this blogger.  Name is returned in
     * first/last order with proper capitalization.
     * @return String blogger's last name
     */
    function getFullName($solid = false) {
        $space = $solid?" ":"";
        
        $fullName = $this->_firstName . $space . $this->_lastName;
        return ucfirst($fullName);
    }


    /**
     * Retrieves the file name stored for this blogger's profile photo.
     * @return String blogger's profile image file name
     */
    function getImage() {
        return $this->_image;
    }


    /**
     * Retrieves the value stored as this blogger's last blog entry.
     * @return String blogger's last blog content
     */
    function getLastBlog() {
        return $this->_lastBlog;
    }


// METHODS - SETTERS


    /**
     * Sets the value of this blogger's first name.
     * @param $value String blogger's first name
     */
    function setFirstName($value) {
        $this->_firstName = $value;
    }


    /**
     * Sets the value of this blogger's last name.
     * @param @value String blogger's last name
     */
    function setLastName($value) {
        $this->_lastName = $value;
    }


    /**
     * Sets the file name stored for this blogger's profile photo.
     * @param $value String blogger's profile image file name
     */
    function setImage($value) {
        $this->_image = $value;
    }


    /**
     * Sets the value stored as this blogger's last blog entry.
     * @param $value String blogger's last blog content
     */
    function setLastBlos($value) {
        $this->_lastBlog = $value;
    }
}