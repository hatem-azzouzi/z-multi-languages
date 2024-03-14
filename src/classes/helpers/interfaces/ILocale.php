<?php

namespace zmultilang\src\classes\helpers\interfaces;

interface ILocale {
    public function get();
    public function set($locale);
    public function addMetaBox($postType);
    public function getLocalePost($postId, $locale);
    public function getLocales();
    public function getCurrentLocale(\WP_Post $post);
}
