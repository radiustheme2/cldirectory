<?php
/**
 * Newsletter section
 */
namespace radiustheme\CLDirectory;

if (!defined('ABSPATH')) {
    exit;
}

?>
<div class="container">
  <div class="footer-cta-bg">
    <div class="row justify-content-between align-items-center">
      <div class="col-auto">
        <div class="footer-cta-content">
          <h2 class="footer-cta-title">
            <?php echo esc_html(RDTheme::$options['footer_cta_banner_title']); ?>
          </h2>
          <p class="footer-cta-desc">
          <?php echo esc_html(RDTheme::$options['footer_cta_banner_text']); ?>
          </p>
        </div>
      </div>
      <div class="col-auto">
        <div class="cta-btn">
          <a href="<?php echo esc_url(RDTheme::$options['footer_cta_btn_url']); ?>" class="btn-lg-outline custom-btn"><?php echo esc_html(RDTheme::$options['footer_cta_btn_text']); ?></a>
        </div>
      </div>
      </div>
      <div class="footer-cta-img-wrapper">
        <img src="<?php echo esc_url( CLDIRECTORY_ASSETS_URL . 'img/cta-ill.svg' ); ?>" width="344" height="140" class="footer-cta-img" alt="<?php echo esc_attr('Shape', 'cldirectory'); ?>">
      </div>
  </div>
</div>
