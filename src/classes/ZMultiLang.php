<?php

namespace zmultilang\src\classes;

use zmultilang\src\classes\translations\ZfwrkTranslations;
use zmultilang\src\classes\languages\ZfwrkLanguages;

class ZMultiLang {

    /**
     * 
     * @var ZfwrkTranslations
     */
    private $_translations;
    /**
     * 
     * @var ZfwrkLanguages
     */
    private $_languages;

    /**
     * 
     * @param ZfwrkTranslations $translations
     * @param ZfwrkLanguages $languages
     */
    public function __construct(ZfwrkTranslations $translations, ZfwrkLanguages $languages) {
        $this->_translations = $translations;
        $this->_languages = $languages;

        add_action('wp_head', [$this, 'hook_header']);
        add_action('wp_footer', [$this, 'hook_footer']);
        if (is_admin()) {
            add_action('admin_menu', [$this, 'pluginSettings']);
            add_filter('pre_update_option_zfwrk_multilang_settings', [$this, 'sanitizeOptions'], 10, 1);
            add_action('before_delete_post', [$this, 'unlinkLocale'], 11, 2);
            add_action('admin_notices', [$this, 'topNotice']);
            add_action('admin_init', [$this, 'dismissNotice']);
            add_filter( 'plugin_action_links', [$this, 'addSettingsLink'], 10, 2 );
            global $pagenow;
            if ( $pagenow == 'post.php' ) {
                add_action( 'init', [$this, 'z_multi_lang_edit_z_multi_lang_edit_block_init'] );
            }
        }
        add_action('init', [$this, 'register']);
        add_filter('wp_nav_menu_objects', [$this, 'zlangMenuFilter'], 10, 2);
        add_filter('zmultilang_translate', [$this, 'ztranslateFilter'], 10, 2);
        add_filter('zmultilang_locale_menu', [$this, 'zmenuFilter'], 10, 2);
        add_filter('zmultilang_current_locale', [$this, 'zlangCurrentLocale'], 10, 1);
        add_action( 'init', [$this, 'z_multi_lang_switch_z_multi_lang_switch_block_init'] );
        add_action( 'init', [$this, 'z_multi_lang_translate_z_multi_lang_translate_block_init'] );
    }

    /**
     * 
     */
    function z_multi_lang_switch_z_multi_lang_switch_block_init() {
	register_block_type( __DIR__ . '/../../blocks/z-multi-lang-switch/build' );
    }

    /**
     * 
     */
    function z_multi_lang_translate_z_multi_lang_translate_block_init() {
	register_block_type( __DIR__ . '/../../blocks/z-multi-lang-translate/build' );
    }

    /**
     * 
     */
    function z_multi_lang_edit_z_multi_lang_edit_block_init() {
        global $wp_version;
        if ($wp_version !== null && $wp_version > '5.9') {
            register_block_type( __DIR__ . '/../../blocks/z-multi-lang-edit/build');
            add_action('wp_print_scripts', [$this, 'shapeSpace_print_scripts']);
        }
    }

    /**
     * 
     */
    function shapeSpace_print_scripts() { 
	?>
	<script>
            var zmultilang_vars = <?php echo json_encode(['box' => $this->_languages->locales->renderMetaBox(true)]); ?>;
	</script>
	<?php
    }

    /**
     * 
     * @param type $links
     * @param type $file
     * @return string
     */
    function addSettingsLink( $links, $file ) {
        $plugin = basename( plugin_dir_path(  dirname( __FILE__ , 2 ) ) );
        if ( $file ==  "$plugin/index.php") {
            $in = '<a href="options-general.php?page=zfwrk-multi-languages-settings-url">' . __('Settings','mtt') . '</a>';
//            array_unshift($links, $in);
            $links[] = $in;
        }
        return $links;
    }

    /**
     * 
     * @param type $options
     * @return type
     */
    function sanitizeOptions( $options ) {
        $options = array_map('array_filter', $options);
        $options = array_filter($options);
        return $options;
    }

    /**
     * 
     * @param int $postId
     * @param \WP_Post $post
     */
    function unlinkLocale(int $postId, $post=null) {

        $zfwrk_multilang_settings = get_option('zfwrk_multilang_settings');

        if ($zfwrk_multilang_settings['post_types']??null && 
                count($zfwrk_multilang_settings['post_types'])) {
            $postTypes = $zfwrk_multilang_settings['post_types'];
        }
        
        if (in_array($post->post_type, $postTypes)) {
            if ($id = get_post_meta( $postId, '_zlang_parent', true )) {
                foreach($this->_translations->locales->locales as $locale) {
                    if (get_post_meta( $id, "_zlang_$locale", true)) {
                        delete_post_meta( $id, "_zlang_$locale");
                    }
                }
            }

            foreach($this->_translations->locales->locales as $locale) {
                if ($id = get_post_meta( $postId, "_zlang_$locale", true )) {
                    if (get_post_meta( $id, '_zlang_parent', true)) {
                        delete_post_meta( $id, '_zlang_parent');
                    }
                }
            }
        }

    }
    
   /**
    * 
    * @global \WP_Post $post
    * @param string $text
    * @param string $locale
    * @return type
    */
    function ztranslateFilter(string $text, string $locale = null) {
       if ($locale === null) {
           global $post;
           $locale = $this->zlangCurrentLocale($post);
       }
       return $this->ztranslate($text, $locale);
    }
    
   /**
    * 
    * @global \WP_Post $post
    * @param string $text
    * @param string $locale
    * @return type
    */
    function zmenuFilter(string $cssID = null, string $locale = null) {
        if ($locale === null) {
           global $post;
           $locale = $this->zlangCurrentLocale($post);
        }
        return wp_nav_menu(
                array(
                    'theme_location'    => $locale,
                    'container'         => 'ul',
                    'container_class'   => 'navbar-collapse collapse',
                    'menu_class'        => 'nav navbar-nav navbar-right',
                    'menu_id' => $cssID ?? 'z-multi-languages-locales-menu'
                )
            );
    }    
    
    /**
     * 
     * @param string $text
     * @param string $locale
     * @return string
     */
    function ztranslate(string $text, string $locale) {
        if ($text) {
            $backtrace = debug_backtrace();

            $args = array(
                'post_type' => 'zfwrk_translation',
                'posts_per_page' => 1,
                'title' => md5($text)
            );
            $query = new \WP_Query($args);
            if($query->post) {
                if ($translation = get_post_meta($query->post->ID, $locale, true)) {
                    return $translation;
                }
                update_post_meta($query->post->ID, $locale, $text);
                update_post_meta($query->post->ID, 'translation_caller', "{$backtrace[0]['file']}:{$backtrace[0]['line']}");
                return $text;
            }

            $postArr = array(
                'post_title' => md5($text),
                'post_type' => 'zfwrk_translation',
                'post_status' => 'publish'
            );
            if($postId = wp_insert_post($postArr)) {
                update_post_meta($postId, $this->_translations->locales->get(), $text);
                update_post_meta($postId, 'translation_caller', "{$backtrace[0]['file']}:{$backtrace[0]['line']}");
            }
        }
        return $text;
    }

    /**
     * 
     * @param type $post
     * @return \zmultilang\src\classes\models\LocaleSwitch
     */
    function zlangGetLocaleSwitcher($post) {
        $currentLocale = $this->zlangCurrentLocale($post);
        $localeSwitcher = array();
        foreach($this->_languages->locales->locales AS $locale) {
            $localeSwitch = new \zmultilang\src\classes\models\LocaleSwitch;
            $localeSwitch->locale = $locale;
            $localePost = $this->_languages->locales->getLocalePost($post, $locale);
            if (!$localePost && $currentLocale != $locale) {
                continue;
            }
            if ($localePost && $localePost->post_status !== 'publish') {
                continue;
            }
            $localeSwitch->current = $currentLocale == $locale;
            $localeSwitch->post = $localePost;
            $localeSwitcher[] = $localeSwitch;
        }
        if (count($localeSwitcher) === 1) {
            return []; // no translation
        }
        return $localeSwitcher;
    }
    
    /**
     * 
     * @global \WP_Post $post
     * @param type $items
     * @param type $args
     * @return type
     */
    function zlangMenuFilter($items, $args) {
        global $post;
        
        $zfwrk_multilang_settings = get_option('zfwrk_multilang_settings');
        foreach($items as $item) { /* @var $item \WP_Post */

            if (($zfwrk_multilang_settings['menu']['translation']['mode']??'') === 'catalog') {
                $item->title = $this->ztranslate($item->title, $this->zlangCurrentLocale($post));
                if ($item->title !== '') {
                    $item->post_title = $item->title;
                }
                $item->url = $this->ztranslate($item->url, $this->zlangCurrentLocale($post));
            }

            if (($zfwrk_multilang_settings['menu']['translation']['mode']??'') === 'automated') {
                foreach($this->zlangGetLocaleSwitcher(get_post($item->object_id)) as $localeSwitch) {
                    if ($localeSwitch->post && $localeSwitch->locale == $this->zlangCurrentLocale($post)) {
                        $item->title = $localeSwitch->post->post_title;
                        if ($item->title !== '') {
                            $item->post_title = $item->title;
                        }
                        $item->url = get_permalink($localeSwitch->post);
                        break;
                    }
                }
            }
        }
        
        return $items;
    }

    /**
     * 
     * @param type $post
     * @return type
     */
    function zlangCurrentLocale($post) {
        $locale = $this->_languages->locales->get();
        if ($post) {
            $locale = $this->_languages->locales->getCurrentLocale($post);        
        }
        return $locale;
    }

    /**
     * 
     * @global \WP_Post $post
     * @param type $attrs
     * @param type $text
     * @return type
     */
    function sc_ztranslate($attrs, $text = null) {
        global $post;
        extract(shortcode_atts(array(
            'locale' => null,
        ), $attrs));
        return $this->ztranslate($text, $locale ?? $this->zlangCurrentLocale($post));
    }

    /**
     * 
     * @global type $post
     * @param array $attrs
     * @return type
     */
    function sc_zswitch($attrs) {
        global $post;
        extract(shortcode_atts(array(
            'type' => 'links', // link, dropdown
        ), $attrs));

        ob_start();
        if (file_exists(__DIR__ . "/../themes/switches/$type.php")) {
            require __DIR__ . "/../themes/switches/$type.php";
        } else {
            require __DIR__ . "/../themes/switches/links.php";        
        }
        $switch = ob_get_contents();
        ob_end_clean();
        return $switch;
    }

    /**
     * 
     */
    function register() {
        add_shortcode('ztranslate', [$this, 'sc_ztranslate']);
        add_shortcode('zswitch', [$this, 'sc_zswitch']);
    }

    /**
     * 
     */
    function pluginSettings() {
        add_options_page('Z Multi-Languages', 'Z Multi-Languages', 'manage_options', 'zfwrk-multi-languages-settings-url', [$this, 'pluginSettingsForm']);
        add_action( 'admin_init', function() {
            register_setting( 'zfwrk-multi-languages-settings-url', 'zfwrk_multilang_settings' );        
        } );
    }

    /**
     * 
     */
    function pluginSettingsForm() {
        $options = get_option('zfwrk_multilang_settings');
        $args = array( 'public' => true, '_builtin' => true );
        $custom_post_types = get_post_types( $args, 'names', 'or' );
        unset($custom_post_types['zfwrk_translation']);
        ob_start();
        require __DIR__ . "/../themes/plugin-settings.php";        
        $form = ob_get_contents();
        ob_end_clean();
        echo $form;
    }

    /**
     * 
     */
    function hook_header() {
        global $post;
        do_action('zmultilang_locale_switcher', $this->zlangGetLocaleSwitcher($post));
        $options = get_option('zfwrk_multilang_settings');
        if (in_array('header', $options['switch']['position']['vertical']??[])) {
            ob_start();
            require __DIR__ . "/../themes/switches/html.php";        
            $switch = ob_get_contents();
            ob_end_clean();
            echo $switch;
        }

        if (in_array('header', $options['menu']['position']??[])) {
            echo $this->zmenuFilter();
        }

    }

    /**
     * 
     */
    function hook_footer() {
        $options = get_option('zfwrk_multilang_settings');
        if (in_array('footer', $options['switch']['position']['vertical']??[])) {
            ob_start();
            require __DIR__ . "/../themes/switches/html.php";        
            $switch = ob_get_contents();
            ob_end_clean();
            echo $switch;
        }

        if (in_array('header', $options['menu']['position']??[])) {
            echo $this->zmenuFilter();
        }

    }
    
    /**
     * 
     */
    function topNotice() {
        $screen = get_current_screen();

        if ($screen->id !== 'settings_page_zfwrk-multi-languages-settings-url' && $screen->id == 'plugins') {
            if ( !get_user_meta( get_current_user_id(), 'zmultilang_top_notice_dismissed' ) ) {
                $notice = @file_get_contents(
                        'https://www.elasticweb.link/wp/zmultilang/notice/top',
                        0,
                        stream_context_create([
                           'http' => [
                               'timeout' => 5
                               ]
                            ]
                        )
                    );
                if ($notice !== false) {
                    echo $notice;                
                } else {
                    echo 
'<div style="padding: 10px;">
  <form action="https://www.paypal.com/donate" method="post" target="_top">
    <input type="hidden" name="hosted_button_id" value="SNNTXGGJZZSDE">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button">
    <img alt="" border="0" src="https://www.paypal.com/en_CA/i/scr/pixel.gif" width="1" height="1">
  </form>
  <p>
    Please donate if you like the plugin to help maintain, improve and add more features to it. Thanks!
  </p>
</div>';   
                }
            }
        }
    }
    
    /**
     * 
     */
    function dismissNotice() {
        if ( isset( $_GET['zmultilang_top_notice_dismissed'] ) ) {
            add_user_meta(get_current_user_id(), 'zmultilang_top_notice_dismissed', 'true', true);            
        }
    }
    
}
