<form role="search" method="get" class="search-form" action="<?= esc_url( home_url( '/' ) ) ?>">
    <label>
        <span class="screen-reader-text"><?= _x( 'Search for:', 'label' ) ?></span>
        <input type="search" class="search-field" placeholder="Search BTF" value="<?= get_search_query() ?>" name="s" title="<?= esc_attr_x( 'Search for:', 'label' ) ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
    </label>
</form>