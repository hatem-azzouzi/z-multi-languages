<?php
$content = $attributes['content'] ?? '';
if ($content) {
    echo do_shortcode("[ztranslate]{$content}[/ztranslate]");    
}
