=== Z Mutli-Languages Plugin ===
Contributors: Hatem Azzouzi
Tags: translation, language, locale, internationalization
Requires at least: 5.0
Tested up to: 6.4.3
Stable tag: 0.1.1
License: GPLv2 or later

# Introduction

Very lightweight multi-languages plugin with a translation catalog. Very easy, quick to setup and to start translating any post type with one click of the mouse.


# 1. Installation

Navigate to Settings / Z Multi-Languages to setup the plugin.

# 1.1. Add the different languages/locales

Default locales are en_CA and fr_CA if not specified. You can override and add any number of languages or locales.

# 1.2. Select the target post types

Default post types are post and page. You may add any new custom type from the checkbox list.

# 1.3. Select the languages switch position and type

This is optional. If selected it will automatically insert the switch in the header and/or footer.

You can also insert a switch shortcode using [zswitch type="links|dropdown"] anywhere when you edit your post.

For recent WP using Gutenberg blocks, you can insert a block by clicking on the [+] and search for "Z Mutli-Languages Switch".

# 1.4. Select the menus translation mode

This is also optional. Menus can be translated automatically by switching the menu item title and url to the translated page title and url. If you select the 
catalog translation mode, you will need to use the catalog to translate the titles and urls of the menu items. By using the catalog or automated mode, you 
don't have to create a menu for each language. If no mode selected or "none" selected, you will have to create a new menu for each language.


# 2. Usage


# 2.1. Create a new post

Once you have updated the settings, create a new post or edit an existing one, add/update your content in the default language (first one in the languages 
list). Once published or updated, you will notice the "Z Multi-Languages" meta box. If it is a newly published post, you need to edit it or simply reload the 
page.

*** You will not be able to translate a post or page until its status changes to draft or publish. ***

Next, to translate the current post to any other language or locale, click on "Translate this page to aa_AA" and enter the translated content. If your are using 
custom permalinks, you should also enter the translated slug for better SEO. Publish the newly translated post and do the same to translate to the next 
language.

# 2.2. Home page setup

If your home page is a wp_template post type template, you will need to create a static page and set it as your home page in Settings / Reading / Your homepage displays. 
You will then be able to translate it the same way as any other page, post or custom post type.

# 2.3. Create menus (optional)

When creating a new menu under Appearance -> Menus, select the target locale or language for Display location under which you want the menu to be displayed. 
When adding an item to the menu, You should then select the page or post in the same locale or language when creating the menu items.

As explained above (1.4.), you have 2 options when creating a menu. You have to select "automated" or "use catalog" if you would like to edit and manage one 
menu for your web site which is more convenient. Otherwise, you can still create a menu for each language. If you have selected "use catalog" mode, will 
continue the reading (3. and 3.3.).


*** You are done! ***



# 3. Translate using the catalog

If you rather want to use a global catalog to translate your content instead of entering it in each translated page or menu, or if you have recurring content 
you want to translate just once and use it everywhere, there is a shortcode that you can use to insert it in the content, then edit the translations in the 
Zfwrk Translations catalog.

Here is how ..
    

# 3.1. Insert translation shortcode

Edit or create a new post. Add a new shortcode and enter the text you want to translate. Always start with the default language post.
[ztranslate]text to translate[/ztranslate]

If you want or need to translate a text for another language than the one of the current post, you can add locale attribute to specify which language you want 
to translate the text to.

[ztranslate locale="ll_LL"]text to translate[/ztranslate]

Then translate the page as described in (3.). You will notice the shortcode is also present in the translated page.

For recent WP using Gutenberg blocks, you can insert a block by clicking on the [+] and search for "Z Mutli-Languages Translate".

# 3.2. Translate the shortcode in the catalog 

Open Zfwrk Translations from the left menu. You will see there is a new translation created. Click Edit to enter the translations and Update.

# 3.3. Translating the menu items

If you have created a menu and selected "use catalog" mode, the translations will be created under Zfwrk Translations once you visit the page containing 
the menu. Just edit each one of them and enter the translated text.


# 4. Advanced users

# 4.1. Languages switch

The languages switch which is automatically inserted in the header and/or footer has a unique attribute ID z-multi-languages-locales-switch you can use to style 
it.

For developers, you can insert the switch anywhere by using the zmultilang_locale_switcher action hook providing an array of all the locales 
each with zmultilang\src\classes\models\LocaleSwitch type.

Here is an example:

```
add_action(
  'zmultilang_locale_swticher', 
  function(zmultilang\src\classes\models\LocaleSwitch $localeSwitcher) {
    foreach($this->zlangGetLocaleSwitcher($post) AS $localeSwitch) {
      if ($localeSwitch->current === false) {
?>
    <a href="<?= get_permalink($localeSwitch->post); ?>" 
        class="<?= $localeSwitch->current ? 'active' : ''; ?>">
        <?= $this->ztranslate($localeSwitch->locale, 
            $localeSwitch->locale === 'en_CA' ? 
            $localeSwitch->locale : $this->zlangCurrentLocale($post)
        );
        ?>
    </a>
<?php
      }
    }
  }
);
```

# 4.2.

Similarly as for the switch, the menu has a unique attribute ID z-multi-languages-locales-menu you can use to style it.

You can also insert the switch anywhere by using the zmultilang_locale_menu action hook.

Here is an example:

```
echo apply_filters('zmultilang_locale_menu', 'z-multi-languages-locales-menu', 'en_CA');
```

# 4.3. Translate anything, anyhwere

You can translate any text anywhere using the filter below in your theme php files and templates.

```
$translation = apply_filters('zmultilang_translate', $text, $locale);
```

$locale argument is optional. If not specified, the post or page current language is used.

Of course, you can also use any WP filter and translate any data before passing it back to WP.

```
add_filter('wp_filter_name', function($data) {
    return = apply_filters('zmultilang_translate', $data);
});
```
