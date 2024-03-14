<?php

namespace zmultilang\src\classes\helpers;

class ZfwrkCustoms
{
    private $_postType = "zfwrk_custom";
    private $_taxonomy = "zfwrk_custom_model";
    private $_singular = "Z Custom";    
    private $_arguments = null;

    /**
     * 
     * @param string $postType
     * @param type $taxonomy
     * @param string $singular
     */
    public function __construct(string $postType, $taxonomy, string $singular, $arguments = null) {
        $this->_postType = $postType;
        $this->_taxonomy = $taxonomy;
        $this->_singular = $singular;
        $this->_arguments = $arguments;
    }
    
    public function register() {
        add_action( 'init', array($this, 'ZfwrkCustomInit') );
        add_action( 'init', array($this, 'ZfwrkCustomTaxonomy'), 0 );
    }

    public function enqueueScript() {}
    
    public function enqueueAdminScript($hook) {}
    
    public function ZfwrkCustomInit() {
        if (!$this->_postType) {
            return;
        }
        
        $labels = array(
            'name' 			=> sprintf( _x( '%s', 'post type general name', 'zfwrkdomain' ), "{$this->_singular}s" ),
            'singular_name' 		=> sprintf( _x( '%s', 'post type singular title', 'zfwrkdomain' ), $this->_singular ),
            'menu_name' 		=> sprintf( __( '%s', 'zfwrkdomain' ), "{$this->_singular}s" ),
            'all_items' 		=> sprintf( __( 'All %s', 'zfwrkdomain' ), "{$this->_singular}s" ),
            'add_new' 			=> sprintf( _x( 'Add New', '%s', 'zfwrkdomain' ), $this->_singular ),
            'add_new_item' 		=> sprintf( __( 'Add New %s', 'zfwrkdomain' ), $this->_singular ),
            'edit_item' 		=> sprintf( __( 'Edit %s', 'zfwrkdomain' ), $this->_singular ),
            'new_item' 			=> sprintf( __( 'New %s', 'zfwrkdomain' ), $this->_singular ),
            'view_item' 		=> sprintf( __( 'View %s', 'zfwrkdomainzfwrkdomain' ), $this->_singular ),
            'items_archive'		=> sprintf( __( '%s Archive', 'zfwrkdomain' ), $this->_singular ),
            'search_items' 		=> sprintf( __( 'Search %s', 'zfwrkdomain' ), "{$this->_singular}s" ),
            'not_found' 		=> sprintf( __( 'No %s found', 'zfwrkdomain' ), "{$this->_singular}s" ),
            'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'zfwrkdomain' ), "{$this->_singular}s" ),
            'parent_item_colon'		=> sprintf( __( '%s Parent', 'zfwrkdomain' ), $this->_singular ),
            );

            $args = array(
                'label'             => sprintf( __( '%s', 'zfwrkdomain' ), "{$this->_singular}s" ),
                'labels'            => $labels,
                'capability_type'   => 'post',
                'hierarchical'      => true,
                'has_archive'       => true,
                'public'            => true,
                '_builtin'          => false,
                'supports'           => array( 'title' ),                        
                'rewrite'           => array(
                                        'slug' => "$this->_postType",
                                        'with_front' => true,
                                        'ep_mask' => EP_PERMALINK,
                                        'feeds' => false,
                                        'pages' => false,
                ),
                'taxonomies'        => array($this->_taxonomy),
                'show_in_nav_menus' => true,
            );
                
            if ($this->_arguments) {
                $args = array_merge($args, $this->_arguments);
            }

            register_post_type( $this->_postType, $args );
    }

    public function ZfwrkCustomTaxonomy() {
        if (!$this->_taxonomy) {
            return;
        }
        
        $labels = array(
            'name'                       => sprintf( _x( '%s Category', 'taxonomy general name', 'zfwrkdomain' ), $this->_singular ),
            'singular_name'              => sprintf( _x( '%s Category', 'taxonomy singular name', 'zfwrkdomain' ), $this->_singular ),
            'search_items'               => __( 'Search Categories', 'zfwrkdomain' ),
            'all_items'                  => __( 'All Categories', 'zfwrkdomain' ),
            'parent_item'                => __( 'Parent Category', 'zfwrkdomain' ),
            'parent_item_colon'          => __( 'Parent Category', 'zfwrkdomain' ),
            'edit_item'                  => __( 'Edit Category', 'zfwrkdomain' ),
            'update_item'                => __( 'Update Category', 'zfwrkdomain' ),
            'add_new_item'               => __( 'Add New Category', 'zfwrkdomain' ),
            'new_item_name'              => __( 'New Category Name', 'zfwrkdomain' ),
            'menu_name'                  => __( 'Categories', 'zfwrkdomain' ),
	);
        
        $rewrite = array(
          'slug' => "$this->_taxonomy",
          'with_front' => true,
          'hierarchical' => true
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'rewrite'           => $rewrite,
        );

        register_taxonomy( $this->_taxonomy, array( $this->_postType ), $args );
    }
    
}

class ZfwrkCustomException extends \Exception { }