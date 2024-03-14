<?php

namespace zmultilang\src\classes\translations;

use zmultilang\src\classes\helpers\NoLocale;
use zmultilang\src\classes\helpers\interfaces\ILocale;

class ZfwrkTranslations extends \zmultilang\src\classes\helpers\ZfwrkCustoms
{
    
    public $postType = "zfwrk_translation";
    public $taxonomy = null;
    public $singular = "Zfwrk Translation";

    /**
     *
     * @var ILocale
     */
    public $locales = null;    
    
    /**
     * 
     */
    public function __construct() {
        $this->locales = new NoLocale();
        add_action('init', array($this, 'registerMenus'));
        if (is_admin())
        {
            add_action( "add_meta_boxes_{$this->postType}", array($this, 'addMetaBox') );
            add_action( "save_post_$this->postType", array($this, 'saveTranslation') );
            add_filter( "manage_{$this->postType}_posts_columns", array($this, 'AddCustomColumns') );
            add_action( "manage_{$this->postType}_posts_custom_column", array($this, 'showCustomColumns') );
            add_action( "pre_get_posts", array($this, 'ExtendAdminSearch' ) );
        }
        parent::__construct(
                $this->postType, 
                $this->taxonomy, 
                $this->singular, 
                [
                    'capabilities' => [
                        'create_posts' => 'do_not_allow'
                        ],
                    'map_meta_cap' => true,
                ]
        );
    }

    /**
     * 
     */
    function registerMenus() {
        register_nav_menus(
            array_combine($this->locales->getLocales(), $this->locales->getLocales())
        );
    }

    /**
     * 
     * @param type $query
     * @return type
     */
    function ExtendAdminSearch( $query ) {
        if ( $query->query['post_type'] != $this->postType )
            return;

        $search_term = $query->query_vars['s'];

        // Set to empty, otherwise it won't find anything
        $query->query_vars['s'] = '';

        if ( $search_term != '' )
        {
            $meta_query = array( 'relation' => 'OR' );

            foreach( $this->locales->getLocales() as $custom_field ) {
                array_push( $meta_query, array(
                    'key' => $custom_field,
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ));
            }
            $query->set( 'meta_query', $meta_query );
        }
    }

    /**
     * 
     * @param type $columns
     * @return string
     */
    public function AddCustomColumns($columns) {
        foreach($this->locales->getLocales() AS $locale) {
            $columns[$locale] = $locale;
        }
        $columns['title'] = 'Translation ID';
        $columns['translation_caller'] = 'Translation caller';
        return $columns;
    }

    /**
     * 
     * @global type $post
     * @param type $name
     */
    public function showCustomColumns($name) {
        global $post;
        foreach($this->locales->getLocales() AS $locale) {
            if ($name == $locale) {
                echo get_post_meta($post->ID, $locale, true);
                break;
            }
        }
        if ($name == 'translation_caller') {
            echo get_post_meta($post->ID, 'translation_caller', true);            
        }
    }
  
    /**
     * 
     * @param type $postId
     * @return type
     */
    public function saveTranslation($postId) {
        if (get_post_type($postId) != $this->postType)
            return;
     
        if ( !wp_verify_nonce( $_POST['translations_meta_box_nonce'], basename(__FILE__) ) ) {
		return $postId; 
	}
        
        foreach($this->locales->getLocales() AS $locale) {
            if (isset($_POST[$locale])) {
                update_post_meta($postId, $locale, $_POST[$locale]);                            
            }
        }
    }
    
    /**
     * 
     */
    public function addMetaBox() {
        add_meta_box(
            'translations-meta-box',
            __( 'Zfwrk Translations', 'zfwrk' ),
            array($this, 'renderMetaBox'),
            $this->postType,
            'normal',
            'default',
        );
    }

    /**
     * 
     */
    public function renderMetaBox() {
        // ask service for metabox with license id
        // ask service permission before submitting and count with license id
        ob_start();
        $file = __FILE__;
        require __DIR__ . '/themes/translations.php';
        $translations = ob_get_contents();
        ob_end_clean();
        echo $translations;
    }
}
