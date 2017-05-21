<?php


class Controller {
    
    
    public function register($f3) {
        // Set environment tokens
        $f3->mset(array(
            'description' => 'Register New Blogger',
            'title' => 'Register',
            'user' => true,
            'current' => $this->makeBlogger()
        ));
    }
    
    
    private function makeBlogger() {
        $current = new Blogger('PertySlick', 'perty_slick@outlook.com');
        $current->setImage('Perty Slick.png');
        $current->setBio('This is my short biography.  Nothing fantastic really.  Just trying to fill up space...');
        $current->setBlogCount(20);
        $current->setLastBlog('This is my short previous blog summary.  Nothing fantastic really.  Just trying to fill up space...');
        
        return $current;
    }
}