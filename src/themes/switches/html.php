<?php
    if ('right' == $options['switch']['position']['horizontal']??'') {
        echo '<div id="z-multi-languages-locales-switch" style="float: right;">';
    } else if ('right' == $options['switch']['position']['horizontal']??'') {
        echo '<div id="z-multi-languages-locales-switch" style="float: left;">';
    } else {
        echo '<div id="z-multi-languages-locales-switch">';
    }
    echo $this->sc_zswitch([
            'type' => $options['switch']['type']??'links'
        ])
        .'</div>';
