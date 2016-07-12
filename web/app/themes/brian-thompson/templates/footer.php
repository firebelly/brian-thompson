<?php
use Firebelly\SiteOptions;
?>

<!-- Brian's contact info in vcard format is built into this footer -->
<footer class="site-footer closed vcard" role="contentinfo">
  <div class="visually-hidden">
    <span class="fn"><?= SiteOptions\get_option('name') ?></span>
  </div>
  <div class="footer-wrap">
    <div class="group-one">
      <div class="title-block footer-block">
        <p class="org"><?= SiteOptions\get_option('org') ?></p>
      </div>
      <div class="social-block footer-block">
        <p>Connect</p>
        <ul class="social-links">
          <li class="social-link">
            <a href="<?= esc_url('https://facebook.com/'.SiteOptions\get_option('facebook_id')) ?>" class="url"><button class="black-arrow"><svg class="social-icon -facebook" role="img"><use xlink:href="#facebook"></use></svg><span class="sr-only">Facebook</span></button></a>
          </li>
          <li class="social-link">
            <a href="<?= esc_url('https://twitter.com/'.SiteOptions\get_option('twitter_id')) ?>" class="url"><button class="black-arrow"><svg class="social-icon -twitter" role="img"><use xlink:href="#twitter"></use></svg><span class="sr-only">Twitter</span></button></a>
          </li>
          <li class="social-link">
            <a class="email" href="<?= esc_url('mailto:'.SiteOptions\get_option('email')) ?>"><button class="black-arrow"><svg class="social-icon -email" role="img"><use xlink:href="#email"></use></svg><span class="sr-only">Email</span></button></a>
          </li>
        </ul>
      </div>
      <div class="contact-block footer-block">
        <p class="email-wrap"><a class="email" href="<?= esc_url('mailto:'.SiteOptions\get_option('email')) ?>"><?= str_replace( '@', ' @ ', SiteOptions\get_option('email') ) ?></a></p>
        <p class="tel"><?= SiteOptions\get_option('phone') ?></p>
      </div>
      <div class="fineprint-block footer-block">
        <p class="disclaimer"><a href="<?= get_permalink(get_page_by_path('disclaimer')) ?>">Disclaimer</a></p>
        <p class="adv"><a href="<?= SiteOptions\get_option('adv') ?>">ADV Part 2a</a></p>
        <div class="copyright"><?= apply_filters('the_content',SiteOptions\get_option('copyright') ) ?></div>
      </div>
    </div>
    <div class="group-two">
      <div class="contact-form-block footer-block">
        <p class="have-questions">Have Questions?</p>
        <a href="<?= get_permalink(get_page_by_path('contact')) ?>"><button class="contact-button black-arrow">Contact Brian</button></a>
      </div>
      <div class="description-block footer-block">
        <?= apply_filters('the_content', SiteOptions\get_option('description')) ?>
      </div>
    </div>
  </div>
</footer>
