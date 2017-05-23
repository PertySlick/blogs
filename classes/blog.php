<?php

/*
 * File Name: blog.php
 * Author: Timothy Roush
 * Date Created: 5/21/17
 * Assignment: The Blogs Site
 * Description:  Class To Store And Manage Blog Data
 */

 /**
  * Blog is an class representing a blog object.  It stores all data
  * applicable to a blog and controls access to said data.  It is to be used
  * in conjuction with the Blogger class.
  *
  * @author Timothy Roush
  * @copyright 2017
  * @version 1.0
  * @see Blogger.php
  */
class Blog {

// FIELDS


    private $_id;
    private $_title;
    private $_content;
    private $_author;
    private $_wordCount;
    private $_dateAdded;
    private $_dateEdited;


// CONSTRUCTOR


    /**
     * Creates an instance of a Blog object to store pertinant information
     * within.
     * @param $id int database record number of this Blog
     * @param $title String title given to this Blog
     * @param $author int database record number of Blog author
     */
    public function __construct($id, $title, $author) {
        $this->setID($id);
        $this->setTitle($title);
        $this->setAuthor($author);
    }


// METHODS - GETTERS


    /**
     * Retrieves the id value stored for this Blog.
     * @return int database record number for this Blog
     */
    public function getID() {
        return $this->_id;
    }


    /**
     * Retrieves the title value stored for this Blog.
     * @return String title for this Blog
     */
    public function getTitle() {
        return $this->_title;
    }


    /**
     * Retrieves the content stored for this Blog.
     * @return String content for this Blog
     */
    public function getContent() {
        return $this->_content;
    }


    /**
     * Retrieves the id value stored for the author of this Blog.
     * @return int database record number for the author of this Blog
     */
    public function getAuthor() {
        return $this->_author;
    }


    /**
     * Retrieves the word count value stored for this Blog.
     * @return int word count for this Blog
     */
    public function getWordCount() {
        return $this->_wordCount;
    }


    /**
     * Retrieves the date this Blog was created.
     * @return String creation date for this Blog
     */
    public function getDateAdded() {
        return $this->_dateAdded;
    }


    /**
     * Retrieves the last date this Blog was edited.
     * @return String last modified date for this Blog
     */
    public function getDateEdited() {
        return $this->_dateEdited;
    }


// METHODS - SETTERS


    /**
     * Sets the value of this Blog's database record number to the supplied
     * value.
     * @param $value int new database record number for this Blog
     */
    public function setID($value) {
        $this->_id = $value;
    }


    /**
     * Sets the value of this Blog's title to the supplied value
     * @param $value String new title for this Blog
     */
    public function setTitle($value) {
        $this->_title = $value;
    }


    /**
     * Sets the value of this Blog's content to the supplied value
     * @param $value String new content for this Blog
     */
    public function setContent($value) {
        $this->_content = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }


    /**
     * Sets the value of this Blog's author to the supplied value
     * @param $value int new author database record number for this Blog
     */
    public function setAuthor($value) {
        $this->_author = $value;
    }


    /**
     * Sets the value of this Blog's word count to the supplied value
     * @param $value int new word count for this Blog
     */
    public function setWordCount($value) {
        $this->_wordCount = $value;
    }


    /**
     * Sets the value of this Blog's creation date to the supplied value
     * @param $value String new creation date for this Blog
     */
    public function setDateAdded($value) {
        $this->_dateAdded = $value;
    }


    /**
     * Sets the value of this Blog's last modified date to the supplied value
     * @param $value String new last modified date for this Blog
     */
    public function setDateEdited($value) {
        $this->_dateEdited = $value;
    }
}