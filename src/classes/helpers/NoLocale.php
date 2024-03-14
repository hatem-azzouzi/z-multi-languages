<?php

namespace zmultilang\src\classes\helpers;

class NoLocale implements interfaces\ILocale {
    public $locale;
    
    public function set($locale) {
        $this->locale = c;
    }
    
    public function get() {
        return $locale;
    }
    
    public function addMetaBox($postType) {
    }

    public function getLocalePost($postId, $locale) {   
    }
    
    public function getLocales() {
    }
    
    public function getCurrentLocale(\WP_Post $post) {
    }
    
}
