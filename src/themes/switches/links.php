<style>
    #zswitch {
        list-style-type: none;
    }
    #zswitch li {
        float: left;
        padding: 5px;
    }
</style>
<ul id="zswitch">
<?php
/* @var $localeSwitch zmultilang\src\classes\models\LocaleSwitch */
foreach($this->zlangGetLocaleSwitcher($post) AS $localeSwitch) {
    echo '<li>';
    if ($localeSwitch->current === false) {
?>
    <a href="<?= get_permalink($localeSwitch->post); ?>" class="<?= $localeSwitch->current ? 'active' : ''; ?>">
<?php } ?>
    <?= $this->ztranslate($localeSwitch->locale, 
        $localeSwitch->locale === 'en_CA' ? '1'.$localeSwitch->locale : $this->zlangCurrentLocale($post)); ?>
<?php
    if ($localeSwitch->current === false) {
?>
    </a>
<?php
    }
    echo '</li>';
}
?>
</ul>
