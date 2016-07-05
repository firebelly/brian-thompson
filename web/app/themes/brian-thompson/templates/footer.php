<?php
use Firebelly\SiteOptions;
$footer = get_page_by_path('footer');
?>

<!-- Brian's contact info in vcard format is built into this footer -->
<footer class="site-footer closed vcard" role="contentinfo">
  <div class="visually-hidden">
    <span class="fn"><?= SiteOptions\get_option('name') ?></span>
  </div>
  <div class="description footer-block">
    <?= apply_filters('the_content',$footer->post_content) ?>
  </div>
  <div class="contact-form footer-block">
    <p class="questions-graph">Have Questions?</p>
    <a href="<?= get_permalink(get_page_by_path('contact')) ?>"><button class="contact-button black-arrow"><div class="arrow-wrap">Contact Brian</div></button></a>
  </div>
  <div class="add-info footer-block">
    <p class="org"><?= SiteOptions\get_option('org') ?></p>
    <p class="disclaimer"><a href="<?= get_permalink(get_page_by_path('disclaimer')) ?>">Disclaimer</a></p>
    <p class="adv"><a href="<?= SiteOptions\get_option('adv') ?>">ADV Part 2a</a></p>
    <div class="copyright"><?= apply_filters('the_content',get_post_meta($footer->ID,'_cmb2_copyright',true)) ?></div>
  </div>
  <div class="connect footer-block">
    <p>Connect</p>
    <ul class="social-links">
      <li class="social-link">
        <a href="<?= esc_url('https://facebook.com/'.SiteOptions\get_option('facebook_id')) ?>" class="url"><button class="black-arrow">F</button></a>
      </li>
      <li class="social-link">
        <a href="<?= esc_url('https://twitter.com/'.SiteOptions\get_option('twitter_id')) ?>" class="url"><button class="black-arrow">T</button></a>
      </li>
      <li class="social-link">
        <a class="email" href="<?= esc_url('mailto:'.SiteOptions\get_option('email')) ?>"><button class="black-arrow">E</button></a>
      </li>
    </ul>
    <p class="email-wrap"><a class="email" href="<?= esc_url('mailto:'.SiteOptions\get_option('email')) ?>"><?= str_replace( '@', ' @ ', SiteOptions\get_option('email') ) ?></a></p>
    <p class="tel"><?= SiteOptions\get_option('phone') ?></p>
  </div>
</footer>
