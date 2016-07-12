<?php
use Firebelly\SiteOptions;
?>

<div class="share">
  <h4>Share</h4>
  <ul class="social-links">
    <li class="social-link">
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= get_permalink( $post->ID ); ?>" target="_blank" class="no-underline"><span class="sr-only">Facebook</span><button class="black-arrow" class="no-underline"><svg class="social-icon -facebook" role="img"><use xlink:href="#facebook"></use></svg><span class="sr-only">Facebook</span></button></a>
    </li>
    <li class="social-link">
      <a href="https://twitter.com/share?text=<?= the_title(); ?>&via=<?=  SiteOptions\get_option('twitter_id') ?>&url=<?= get_permalink( $post->ID ) ?>" target="_blank" class="no-underline"><button class="black-arrow"><svg class="social-icon -twitter" role="img"><use xlink:href="#twitter"></use></svg><span class="sr-only">Twitter</span></button></a>
    </li>
    <li class="social-link">
        <a href="mailto:?subject=<?php echo urlencode( get_the_title() ).'&body='.urlencode('A post from '.bloginfo('name').': ').urlencode( get_permalink() ); ?>" class="no-underline"><button class="black-arrow"><svg class="social-icon -email" role="img"><use xlink:href="#email"></use></svg><span class="sr-only">Email</span></button></button></a>
      </li>
  </ul>
</div>