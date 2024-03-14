<?php

namespace zmultilang\src\classes\helpers;

class ZfwrkLocales implements interfaces\ILocale {

    private $_locale = null;
    public $default = false;
    public $locales = null;
    
    /**
     * 
     * @param string $locale
     */
    public function __construct(string $locale) {
       $postId = $_GET['post']??$_POST['post_ID']??null;
       if ($postId) {
            $this->_locale = get_post_meta($postId, '_zlang_', true);
       }
       if (! $this->_locale) {
           $this->default = true;
            $this->_locale = $locale;
       }
       if (is_admin()) {
            add_filter('zmultilang_languages_metabox', [$this, 'renderMetaBox']);
       }
    }

    /**
     * 
     * @param type $locale
     */
    public function set($locale) {
         $this->_locale = $locale;
    }

    /**
     * 
     * @return type
     */
    public function get() {
        return $this->_locale;
    }
    
    /**
     * 
     * @param \WP_Post $post
     * @return type
     */
    public function getCurrentLocale(\WP_Post $post) {
        if($locale = get_post_meta($post->ID, "_zlang_", true)) {
            return $locale;
        }
        return  $this->_locale;
    }
    
    /**
     * 
     * @return type
     */
    public function getLocales() {
        return $this->locales;
    }
    
    /**
     * 
     * @param \WP_Post $post
     * @return type
     */
    public function getDefaultLocalePostId(\WP_Post $post) {
        $localePostId = get_post_meta($post->ID, "_zlang_parent", true);
        return $localePostId?:$post->ID;
    }
    
    /**
     * 
     * @param type $post
     * @param type $locale
     * @return null
     */
    public function getLocalePost($post, $locale) {
        if ($post) {
            $childPostId = get_post_meta($post->ID, "_zlang_$locale", true);
            if ($childPostId) {
                return get_post($childPostId);
            }
            $parentPostId = get_post_meta($post->ID, "_zlang_parent", true);
            if ($parentPostId) {
                if($locale == get_post_meta($parentPostId, "_zlang_", true)) {
                    return get_post($parentPostId);
                }
                $otherPostId = get_post_meta($parentPostId, "_zlang_$locale", true);
                if ($otherPostId) {
                    return get_post($otherPostId);
                }
            }
        }
        return null;            
    }
    
    /**
     * 
     * @param type $postType
     */
    public function addMetaBox($postType) {
        global $wp_version, $pagenow;
        if (($wp_version !== null && $wp_version > '5.9') === false) {
            if ( $pagenow == 'post.php' ) {
                add_meta_box(
                    'z-multi-languages-meta-box',
                    'Z Multi-Languages',
                    array($this, 'renderMetaBox'),
                    $postType,
                    'side',
                    'core'
                );
            }
        }
    }

    /**
     * 
     * @global type $pagenow
     * @param type $return
     * @return type
     */
    public function renderMetaBox($return = false) {
        global $pagenow;
        if ( $pagenow == 'post.php' ) {
            $box = "Current page language :  $this->_locale";
            ob_start();
            require __DIR__ . '/themes/languages.php';
            $box .= ob_get_contents();
            ob_end_clean();
            if ($return === true) {
                return $box;
            }
            echo $box;
        }
    }

}
