<?php

class Blogger {
    const DEFAULT_IMAGE = "male.png";

    protected $firstName;
    protected $lastName;
    protected $blogCount;
    protected $image;
    protected $lastBlog;

    function __construct($firstName, $lastName) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }


// METHODS - GETTERS


    /**
     * Retrieves the value stored as this blogger's first name.
     * @return String blogger's first name
     */
    function getFirstName() {
        return $this->firstName;
    }


    /**
     * Retrieves the value stored as this blogger's last name.
     * @return String blogger's last name
     */
    function getLastName() {
        return $this->lastName;
    }


    /**
     * Retrieves the full name stored for this blogger.  Name is returned in
     * first/last order with proper capitalization.
     * @return String blogger's last name
     */
    function getFullName($solid = false) {
        $space = $solid?" ":"";
        
        $fullName = $this->firstName . $space . $this->lastName;
        return ucfirst($fullName);
    }


    /**
     * Retrieves the value representing the number of blogs this blogger has.
     * @return int blogger's blog count
     */
    function getBlogCount() {
        return $this->blogCount;
    }


    /**
     * Retrieves the file name stored for this blogger's profile photo.
     * @return String blogger's profile image file name
     */
    function getImage() {
        return $this->image;
    }


    /**
     * Retrieves the value stored as this blogger's last blog entry.
     * @return String blogger's last blog content
     */
    function getLastBlog() {
        return $this->lastBlog;
    }


// METHODS - SETTERS


    /**
     * Sets the value of this blogger's first name.
     * @param $value String blogger's first name
     */
    function setFirstName($value) {
        $this->firstName = $value;
    }


    /**
     * Sets the value of this blogger's last name.
     * @param @value String blogger's last name
     */
    function setLastName($value) {
        $this->lastName = $value;
    }


    /**
     * Sets the value representing the number of blogs this blogger has.
     * @param $value int blogger's blog count
     */
    function setBlogCount($value) {
        $this->blogCount = $value;
    }


    /**
     * Sets the file name stored for this blogger's profile photo.
     * @param $value String blogger's profile image file name
     */
    function setImage($value) {
        $this->image = $value;
    }


    /**
     * Sets the value stored as this blogger's last blog entry.
     * @param $value String blogger's last blog content
     */
    function setLastBlos($value) {
        $this->lastBlog = $value;
    }
}