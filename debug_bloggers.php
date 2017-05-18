<?php

$blogger1 = new Blogger("Jack", "Bauer");
$blogger1->setBlogCount = 24;
$blogger1->setImage = "bauer.jpg";
$blogger1->setLastBlog = "This is my official blog of useless hyperbol and banter.";

$blogger2 = new Blogger("Clark", "Kent");
$blogger2->setBlogCount = 10;
$blogger2->setImage = "superman.jpg";
$blogger2->setLastBlog = "Just your everyday simple reported trying to do something extraordinary";

$bloggers = array();
$bloggers[] = array('firstName' => 'Jack',
                    'lastName' => 'Bauer',
                    'blogCount' => 24,
                    'image' => 'bauer.jpg',
                    'lastBlog' => 'This is my official blog of useless hyperbol and banter.'
                    );
$bloggers[] = array('firstName' => 'Clark',
                    'lastName' => 'Kent',
                    'blogCount' => 10,
                    'image' => 'superman.jpg',
                    'lastBlog' => 'Just your everyday simple reported trying to do something extraordinary'
                    );