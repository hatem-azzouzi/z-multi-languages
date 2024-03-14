<?php
global $post;
?>
<input type="hidden" name="translations_meta_box_nonce" value="<?php echo wp_create_nonce( basename($file) ); ?>">
<?php
foreach($this->locales->getLocales() AS $i=>$locale)
{
?>
<div>
    <label><?= $locale; ?></label><br>
    <textarea <?= $i==0 ? 'disabled':''?> name="<?= $locale; ?>" cols="100" rows="5"><?= get_post_meta($post->ID, $locale, true); ?></textarea>    
</div>
<?php
}
