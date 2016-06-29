<?php
use Firebelly\SiteOptions;
?>

<div class="share">
  <h4>Share</h4>
  <ul class="social-links">
    <li class="social-link">
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= get_permalink( $post->ID ); ?>" target="_blank"><span class="sr-only">Facebook</span><button>F</button></a>
    </li>
    <li class="social-link">
      <a href="https://twitter.com/share?text=<?= the_title(); ?>&via=<?=  SiteOptions\get_option('twitter_id') ?>&url=<?= get_permalink( $post->ID ) ?>" target="_blank"><span class="sr-only">Twitter</span><button>T</button></svg></a>
    </li>
    <li class="social-link">
        <a href="mailto:?subject=<?php echo urlencode( get_the_title() ).'&body='.urlencode('A post from '.bloginfo('name').': ').urlencode( get_permalink() ); ?>"><button>E</button></a>
      </li>
  </ul>
</div>