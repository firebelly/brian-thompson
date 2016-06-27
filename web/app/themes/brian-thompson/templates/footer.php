<?php
use Firebelly\SiteOptions;
$footer = get_page_by_path('footer');
?>

<!-- Brian's contact info in vcard format is built into this footer -->
<footer class="site-footer vcard" role="contentinfo">
    <div class="visually-hidden">
      <span class="fn"><?= SiteOptions\get_option('name') ?></span>
    </div>
  <div class="description">
    <a href="<?= bloginfo('url'); ?>" class="url">
      <svg class="icon-placeholder" role="img"><use xlink:href="#icon-placeholder"></use></svg> 
    </a>
    <?= apply_filters('the_content',$footer->post_content) ?>
  </div>
  <div class="questions">
  <h4>Have Questions?</h4>
  <a href="<?= get_permalink(get_page_by_path('contact')) ?>"><button>Contact</button></a>
  </div>
  <address class="additional-info">
    <h4 class="org"><?= SiteOptions\get_option('org') ?></h4>
    <div class="tel"><?= SiteOptions\get_option('phone') ?></div>
    <div class="email_wrap"><a class="email" href="<?= esc_url('mailto:'.SiteOptions\get_option('email')) ?>"><?= SiteOptions\get_option('email') ?></a></div>
    <div class="disclaimer"><a href="<?= get_permalink(get_page_by_path('disclaimer')) ?>">Disclaimer</a></div>
    <div class="adv"><a href="<?= SiteOptions\get_option('adv') ?>">ADV Part 2a</a></div>
    <div class="copyright"><?= apply_filters('the_content',get_post_meta($footer->ID,'_cmb2_copyright',true)) ?></div>
  </div>
  <div class="connect">
    <h4>Connect</h4>
    <ul class="connect-links">
      <li class="connect-link">
        <a href="<?= esc_url('https://facebook.com/'.SiteOptions\get_option('facebook_id')) ?>" class="url"><button>F</button></a>
      </li>
      <li class="connect-link">
        <a href="<?= esc_url('https://twitter.com/'.SiteOptions\get_option('twitter_id')) ?>" class="url"><button>T</button></a>
      </li>
    </ul>
  </div>
</footer>
