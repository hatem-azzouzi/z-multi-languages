<style>
    input.regular-button {
    }
</style>
<script>
    function addZLanguage() {
        var zlanguages = document.getElementById("zlanguages");
        var input = document.createElement('input');
        input.type = "text";
        input.name = "zfwrk_multilang_settings[zlanguages][]";
        input.placeholder = "e.g. en_CA";
        zlanguages.appendChild(input);
        input = document.createElement('input');
        input.type = "button";
        input.value = "delete";
        input.onclick = function() {
            deleteZLanguage(this);
        };
        zlanguages.appendChild(input);
        br = document.createElement('br');
        zlanguages.appendChild(br);
    }
    function deleteZLanguage(e) {
        e.previousElementSibling.remove();
        e.nextElementSibling.remove();
        e.remove();            
    }
</script>
<div class="wrap" style="float: left;">
    <h2>Z Multi-Languages Settings</h2>
    <form method="post" action="options.php">
        <?php settings_fields('zfwrk-multi-languages-settings-url'); ?>
        <?php do_settings_sections('zfwrk-multi-languages-settings-url'); ?>

    <table class="form-table">

        <tr>
            <th>
                <label>Languages / Locales:</label>
                <input type="button" onclick="addZLanguage()" value="add">
                <br>
            </th>
            <td id="zlanguages">
            <?php if ($options['zlanguages']??null && count($options['zlanguages'])) { 
                foreach($options['zlanguages']??[] as $i=>$lang) {
                ?>
                    <input 
                        type="text" 
                        name="zfwrk_multilang_settings[zlanguages][]" 
                        value="<?php echo $lang; ?>">
                    <?php if ($i) { ?>
                    <input 
                        type="button" 
                        onclick="deleteZLanguage(this)" 
                        value="delete">
                    <?php } ?>
                    <br>
                <?php }
                } else { ?>
                <input 
                    type="text" 
                    name="zfwrk_multilang_settings[zlanguages][]" 
                    value=""
                    placeholder="e.g. en_CA"> (default)
                <br>
            <?php } ?>
            </td>
        </tr>

        <tr>
            <th><label for="second_field_id">Post types to translate:</label></th>
            <td>
                <?php foreach($custom_post_types as $postType) { ?>
                    <input 
                        <?= in_array($postType, $options['post_types']??[]) ? 'checked':''?>
                        type ="checkbox" 
                        id="<?php echo $postType; ?>" 
                        name="zfwrk_multilang_settings[post_types][]" 
                        value="<?php echo $postType; ?>">
                    <label for="<?php echo $postType; ?>"><?php echo $postType; ?></label>
                    <br>
                <?php } ?>
            </td>
        </tr>

        <tr>
            <th><label for="third_field_id">Language Switch position:</label></th>
            <td>
                <input 
                    <?= in_array('header', $options['switch']['position']['vertical']??[]) ? 'checked':''?>
                    type ="checkbox" 
                    id="zheaderswitch" 
                    name="zfwrk_multilang_settings[switch][position][vertical][]" 
                    value="header">
                <label for="zheaderswitch">header</label>
                <br>
                <input 
                    <?= in_array('footer', $options['switch']['position']['vertical']??[]) ? 'checked':''?>
                    type ="checkbox" 
                    id="zfooterswitch" 
                    name="zfwrk_multilang_settings[switch][position][vertical][]" 
                    value="footer">
                <label for="zfooterswitch">footer</label>
                <br>
                <input 
                    <?= 'left' == ($options['switch']['position']['horizontal']??null) ? 'checked':''?>
                    type ="radio" 
                    id="zleftswitch" 
                    name="zfwrk_multilang_settings[switch][position][horizontal]" 
                    value="left">
                <label for="zleftswitch">left</label>
                <br>
                <input 
                    <?= 'right' == ($options['switch']['position']['horizontal']??null) ? 'checked':''?>
                    type ="radio" 
                    id="zrightswitch" 
                    name="zfwrk_multilang_settings[switch][position][horizontal]" 
                    value="right">
                <label for="zrightswitch">right</label>
                <br>
            </td>
        </tr>

        <tr>
            <th><label for="fourth_field_id">Language Switch type:</label></th>
            <td>
                <input 
                    <?= 'links' == ($options['switch']['type']??null) ? 'checked':''?>
                    type ="radio" 
                    id="switchlinks" 
                    name="zfwrk_multilang_settings[switch][type]" 
                    value="links">
                <label for="switchlinks">links</label>
                <br>
                <input 
                    <?= 'dropdown' == ($options['switch']['type']??null) ? 'checked':''?>
                    type ="radio" 
                    id="switchdropdown" 
                    name="zfwrk_multilang_settings[switch][type]" 
                    value="dropdown">
                <label for="switchdropdown">dropdown</label>
                <br>
            </td>
        </tr>

        <tr>
            <th><label for="fourth_field_id">Menu items translation mode:</label></th>
            <td>
                <input 
                    <?= 'none' == ($options['menu']['translation']['mode']??null) ? 'checked':''?>
                    type ="radio" 
                    id="menu-none" 
                    name="zfwrk_multilang_settings[menu][translation][mode]" 
                    value="none">
                <label for="menu-none">none</label>
                <br>
                <input 
                    <?= 'automated' == ($options['menu']['translation']['mode']??null) ? 'checked':''?>
                    type ="radio" 
                    id="menu-automated" 
                    name="zfwrk_multilang_settings[menu][translation][mode]" 
                    value="automated">
                <label for="menu-automated">automated</label>
                <br>
                <input 
                    <?= 'catalog' == ($options['menu']['translation']['mode']??null) ? 'checked':''?>
                    type ="radio" 
                    id="menu-catalog" 
                    name="zfwrk_multilang_settings[menu][translation][mode]" 
                    value="catalog">
                <label for="menu-catalog">use catalog</label>
                <br>
            </td>
        </tr>

        <tr>
            <th><label for="third_field_id">Menu position:</label></th>
            <td>
                <input 
                    <?= in_array('header', $options['menu']['position']??[]) ? 'checked':''?>
                    type ="checkbox" 
                    id="zheadermenu" 
                    name="zfwrk_multilang_settings[menu][position][]" 
                    value="header">
                <label for="zheadermenu">header</label>
                <br>
                <input 
                    <?= in_array('footer', $options['menu']['position']??[]) ? 'checked':''?>
                    type ="checkbox" 
                    id="zfootermenu" 
                    name="zfwrk_multilang_settings[menu][position][]" 
                    value="footer">
                <label for="zfootermenu">footer</label>
                <br>
            </td>
        </tr>

    </table>

    <?php submit_button(); ?>
        
    </form>

<?php

$notice = @file_get_contents(
        'https://www.elasticweb.link/wp/zmultilang/notice/settings',
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
?>

</div>

<?php if (file_exists(__DIR__ . '/../../readme.txt')) { ?>
    <div style="float: right;">
        <h2>Help</h2>
        <?php
            echo '<pre>' . str_replace(
                    ['<', '>'],
                    ['&#60;', '&#62;'],
                    file_get_contents(__DIR__ . '/../../readme.txt'))
                    . 
                 '</pre>';
        ?>
    </div>
<?php } ?>
