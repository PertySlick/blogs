<?php

class Blogger {
    const DEFAULT_IMAGE = "anon.png";

    private $_id;
    private $_userName;
    private $_email;
    private $_image;
    private $_bio;
    private $_blogCount;
    private $_lastBlog;


// METHODS - CONSTRUCTOR


    /**
     * Creates an instance of a Blogger object.  This object is used to store
     * commonly used details of a site user.
     * @param $id int row id of blogger in database
     * @param $userName String blogger's user name
     * @param $email String blogger's email eddress
     */
    function __construct($id, $userName, $email)
    {
        $this->setID($id);
        $this->setUserName($userName);
        $this->setEmail($email);
    }


// METHODS - GETTERS


    /**
     * Retrieves the value stored as this blogger's database ID.
     * @return int blogger's database id
     */
    function getID()
    {
        return $this->_id;
    }


    /**
     * Retrieves the value stored as this blogger's user name.
     * @return String blogger's user name
     */
    function getUserName()
    {
        return $this->_userName;
    }


    /**
     * Retrieves the value stored as this blogger's email address.
     * @return String blogger's email
     */
    function getEmail()
    {
        return $this->_email;
    }


    /**
     * Retrieves the file name stored for this blogger's profile photo.
     * @return String blogger's profile image file name
     */
    function getImage()
    {
        return $this->_image;
    }


    /**
     * Retrieves the value stored for this blogger's profile bio.
     * @return String blogger's biography
     */
    function getBio()
    {
        return $this->_bio;
    }


    /**
     * Retrieves the value stored as this blogger's blog count.
     * @return int blogger's blog ccount
     */
    function getBlogCount()
    {
        return $this->_blogCount;
    }


    /**
     * Retrieves the value stored as this blogger's last blog entry.
     * @return String blogger's last blog content
     */
    function getLastBlog()
    {
        return $this->_lastBlog;
    }


// METHODS - SETTERS


    /**
     * Sets the value of this blogger's database id.
     * @param $value int blogger's database id
     */
    function setID($value)
    {
        $this->_id = $value;
    }


    /**
     * Sets the value of this blogger's user name.
     * @param $value String blogger's user name
     */
    function setUserName($value)
    {
        $this->_userName = $value;
    }


    /**
     * Sets the value of this blogger's email address.
     * @param $value String blogger's email address
     */
    function setEmail($value)
    {
        $this->_email = $value;
    }


    /**
     * Sets the file name stored for this blogger's profile photo.
     * @param $value String blogger's profile image file name
     */
    function setImage($value)
    {
        $this->_image = $value;
    }


    /**
     * Sets the value of this blogger's bio.
     * @param $value String blogger's bio description
     */
    function setBio($value)
    {
        $this->_bio = $value;
    }


    /**
     * Sets the value of this blogger's current blog count.
     * @param $value int number of blogs in database for this blog
     */
    function setBlogCount($value)
    {
        $this->_blogCount = $value;
    }


    /**
     * Sets the value stored as this blogger's last blog entry.
     * @param $value String blogger's last blog content
     */
    function setLastBlog($value)
    {
        $this->_lastBlog = $value;
    }
}