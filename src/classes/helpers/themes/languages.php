<ul>
    <?php
    global $post;
    foreach($this->locales AS $locale) {
        if ($locale == $this->get()) continue;
        $localePost = $this->getLocalePost($post, $locale);
        $defaultPostId = $this->getDefaultLocalePostId($post);
        if ($localePost) {
    ?>
        <li>
            <a href="/wp-admin/post.php?post=<?=$localePost->ID?>&action=edit">Edit translated page: <strong><?= $locale;?></strong></a>
        </li>
        <?php } else { ?>
        <li>
            <a href="/wp-admin/post.php?post=<?=$defaultPostId?>&action=edit&zaction=ztranslate&zlang=<?=$locale?>">Translate this page to <strong><?= $locale;?></strong></a>
        </li>
        <?php } ?>
    <?php } ?>
</ul>
