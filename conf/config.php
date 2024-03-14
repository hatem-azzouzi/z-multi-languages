<?php

/* @var $injector zmultilang\src\classes\resources\Injector */

$languages = [
    'en_CA',
    'fr_CA',
    ];
$postTypes = [
    'post',
    'page'    
];

$zfwrk_multilang_settings = get_option('zfwrk_multilang_settings');

if ($zfwrk_multilang_settings['zlanguages']??null && 
        count($zfwrk_multilang_settings['zlanguages'])) {
    $languages = $zfwrk_multilang_settings['zlanguages'];
}

if ($zfwrk_multilang_settings['post_types']??null && 
        count($zfwrk_multilang_settings['post_types'])) {
    $postTypes = $zfwrk_multilang_settings['post_types'];
}

return array(

    zmultilang\src\classes\helpers\ZfwrkLocales::class => function() use ($languages) {
        $locales = new zmultilang\src\classes\helpers\ZfwrkLocales(
                $languages[0]
                );
        $locales->locales = $languages;
        return $locales;
    },

    zmultilang\src\classes\languages\ZfwrkLanguages::class => function() use ($postTypes) {
        global $injector;
        /* @var $locales zmultilang\src\classes\helpers\ZfwrkLocales */
        $locales = $injector->resource(zmultilang\src\classes\helpers\ZfwrkLocales::class);
        $languages = new zmultilang\src\classes\languages\ZfwrkLanguages($postTypes, $locales->locales[0]);
        $languages->locales = $locales;
        return $languages;
    },
            
    \zmultilang\src\classes\translations\ZfwrkTranslations::class => function() {
        global $injector;
        $translations = new \zmultilang\src\classes\translations\ZfwrkTranslations();
        $translations->postType = 'zfwrk_translation';
        $translations->taxonomy = null;
        $translations->singular ='Zfwrk Translation';
        $translations->locales = $injector->resource(zmultilang\src\classes\helpers\ZfwrkLocales::class);
        $translations->register();
        return $translations;
    },
            
    \zmultilang\src\classes\ZMultiLang::class => function() {
        global $injector;
        return new \zmultilang\src\classes\ZMultiLang(
            $injector->resource(\zmultilang\src\classes\translations\ZfwrkTranslations::class),
            $injector->resource(zmultilang\src\classes\languages\ZfwrkLanguages::class)
        );
    },
            
);
