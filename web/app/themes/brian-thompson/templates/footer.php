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
            <a href="<?= esc_url('https://facebook.com/'.SiteOptions\get_option('facebook_id')) ?>" class="url"><button class="arrow -right -black -small"><svg class="social-icon -facebook" role="img"><use xlink:href="#facebook"></use></svg><span class="sr-only">Facebook</span></button></a>
          </li>
          <li class="social-link">
            <a href="<?= esc_url('https://twitter.com/'.SiteOptions\get_option('twitter_id')) ?>" class="url"><button class="arrow -right -black -small"><svg class="social-icon -twitter" role="img"><use xlink:href="#twitter"></use></svg><span class="sr-only">Twitter</span></button></a>
          </li>
          <li class="social-link">
            <a class="email" href="<?= esc_url('mailto:'.SiteOptions\get_option('email')) ?>"><button class="arrow -right -black -small"><svg class="social-icon -email" role="img"><use xlink:href="#email"></use></svg><span class="sr-only">Email</span></button></a>
          </li>
        </ul>
      </div>
      <div class="contact-block footer-block">
        <p class="email-wrap"><a class="email" href="<?= esc_url('mailto:'.SiteOptions\get_option('email')) ?>"><?= str_replace( '@', ' @ ', SiteOptions\get_option('email') ) ?></a></p>
        <p class="tel"><?= SiteOptions\get_option('phone') ?></p>
      </div>
      <div class="fineprint-block footer-block">
        <p class="disclaimer"><a class="open-popup fake-link" data-content="#disclaimer" href="#disclaimer">Disclaimer</a></p>
        <p class="adv"><a href="<?= SiteOptions\get_option('adv') ?>">ADV Part 2a</a></p>
        <div class="copyright"><p>BTT &copy;<?php echo date("Y") ?></p></div>
      </div>
    </div>
    <div class="group-two">
      <div class="contact-form-block footer-block">
        <p class="have-questions">Have Questions?</p>
        <a class="open-popup fake-link" data-content="#contact" href="/contact"><button class="contact-button arrow -right -black -small" data-content="#contact">Contact Brian</button></a>
        <p class="stay-informed">Stay Informed</p>
        <a class="open-popup fake-link" data-content="#sign-up" href="#sign-up"><button class="contact-button arrow -right -black -small">News Signup</button></a>
      </div>
      <div class="description-block footer-block">
        <?= apply_filters('the_content', SiteOptions\get_option('description')) ?>
        <div class="cfp">
          <img src="<?= \Roots\Sage\Assets\asset_path('images/cfp.png'); ?>">
        </div>
      </div>
    </div>
  </div>
</footer>
