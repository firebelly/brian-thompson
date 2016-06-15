<nav class="category-nav">
  <ul class="categories">

    <?php $active_class = is_home() ? ' current-menu-item' : ''; ?>
    <li class="category<?= $active_class ?>"><a href="<?= get_permalink( get_option('page_for_posts' ) ); ?>"><?= __('All','sage'); ?></a></li>

    <?php
    $categories = get_categories();
    foreach ($categories as $category) : 
      $active_class = is_category($category->term_id) ? ' current-menu-item' : ''
      ?>
      <li class="category<?= $active_class ?>"><a href="<?= get_category_link($category->term_id); ?>"><?= $category->name ?></a></li>
    <?php endforeach; ?>
  <ul>
</nav>

