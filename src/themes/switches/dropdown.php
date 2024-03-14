<?php
$localeSwitches = $this->zlangGetLocaleSwitcher($post);
if ($localeSwitches) { ?>
<select id="zswitch">
    <?php
    foreach($localeSwitches AS $localeSwitch) { /* @var $localeSwitch zmultilang\src\classes\models\LocaleSwitch */
    ?>
        <option value="<?= get_permalink($localeSwitch->post); ?>" <?= $localeSwitch->current ? 'selected' : ''; ?>>
            <?= $this->ztranslate($localeSwitch->locale, 
                $localeSwitch->locale === 'en_CA' ? $localeSwitch->locale : $this->zlangCurrentLocale($post)); ?>
        </option>
    <?php
    }
    ?>
</select>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('select#zswitch');
        select.addEventListener('change',function() {
            if (select.value) {
                window.location = select.value;
            }
        });
    });
</script>
<?php } ?>