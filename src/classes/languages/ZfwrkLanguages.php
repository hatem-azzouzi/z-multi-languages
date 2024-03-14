<?php

namespace zmultilang\src\classes\languages;

use zmultilang\src\classes\helpers\NoLocale;
use zmultilang\src\classes\helpers\interfaces\ILocale;

class ZfwrkLanguages {

    public $locale;
    
    /**
     *
     * @var WP_Post
     */
    private $post = null;

    /**
     *
     * @var ILocale
     */
    public $locales = null;

    /**
     * 
     * @param type $postTypes
     * @param type $locale
     * @return type
     */
    public function __construct($postTypes = array(), $locale) {
        $this->locales = new NoLocale($locale);
        $this->locale = $locale;
        $post_id = ( isset($_GET['post']) ? absint( $_GET['post'] ) : (isset($_POST['post']) ? absint( $_POST['post'] ) : absint( $_POST['post_ID'] ?? 0 )) );
        if (!$post_id) {
            return;
        }
        $this->post = get_post( $post_id );
        if (!in_array($this->post->post_type, $postTypes)) {
            return;
        }
         
        if (is_admin()) {
            add_action( 'admin_menu', array($this, 'translatePage') );
            add_action( "add_meta_boxes_{$this->post->post_type}", array($this, 'addMetaBox') );
            add_action( "save_post_{$this->post->post_type}", array($this, 'saveZfwrkPage') );
        }
    }

    /**
     * 
     * @global type $wpdb
     */
    public function translatePage() {
        if(!get_post_meta($this->post->ID, '_zlang_', true)) {
            update_post_meta($this->post->ID, '_zlang_', $this->locale);
        }
                
        if (isset($_GET['zaction']) && $_GET['zaction'] == 'ztranslate') {
            global $wpdb;
                        
            $current_user = wp_get_current_user();
            $new_post_author = $current_user->ID;

            if (isset( $this->post ) && $this->post != null) {

                $args = array(
                    'comment_status' => $this->post->comment_status,
                    'ping_status'    => $this->post->ping_status,
                    'post_author'    => $new_post_author,
                    'post_content'   => $this->post->post_content,
                    'post_excerpt'   => $this->post->post_excerpt,
                    'post_name'      => $this->post->post_name,
                    'post_parent'    => $this->post->post_parent,
                    'post_password'  => $this->post->post_password,
                    'post_status'    => 'draft',
                    'post_title'     => $this->post->post_title . (isset($_GET['copy']) ? " [copy]" : " [{$_GET['zlang']}]"),
                    'post_type'      => $this->post->post_type,
                    'to_ping'        => $this->post->to_ping,
                    'menu_order'     => $this->post->menu_order
                );

                $new_post_id = wp_insert_post( $args );

                $taxonomies = get_object_taxonomies($this->post->post_type);
                foreach ($taxonomies as $taxonomy) {
                    $post_terms = wp_get_object_terms($this->post->ID, $taxonomy, array('fields' => 'slugs'));
                    wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                }

                $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id={$this->post->ID}");
                if (count($post_meta_infos) != 0) {
                        $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                        foreach ($post_meta_infos as $meta_info)
                        {
                            $meta_key = $meta_info->meta_key;
                            if( $meta_key == '_wp_old_slug' ) continue;
                            if( strpos($meta_key, '_zlang_') !== false ) continue;
                            $meta_value = addslashes($meta_info->meta_value);
                            $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                        }
                        $sql_query.= implode(" UNION ALL ", $sql_query_sel);
                        $wpdb->query($sql_query);
                }
                
                if (!isset($_GET['copy'])) {
                    update_post_meta($new_post_id, '_zlang_', $_GET['zlang']);
                    update_post_meta($new_post_id, '_zlang_parent', $this->post->ID);
                    update_post_meta($this->post->ID, '_zlang_'.$_GET['zlang'], $new_post_id);                    
                }
                
                wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
            }
        }
    }

    /**
     * 
     * @param type $postId
     * @return type
     */
    public function saveZfwrkPage($postId) {
        if (get_post_type($postId) != $this->post->post_type) {
            return;            
        }

        if(!get_post_meta($this->post->ID, '_zlang_', true)) {
            update_post_meta($postId, '_zlang_', $this->locale);
        }
    }

    /**
     * 
     */
    public function addMetaBox() {
        $this->locales->addMetaBox($this->post->post_type);
    }
    
}
