<?php
/**
 * The template to display the Social profile
 *
 * @author  RadiousTheme
 * @package classified-listing/Templates
 * @version 1.5.72
 * @var array $social_profiles
 */

use Rtcl\Helpers\Functions;
use Rtcl\Resources\Options;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!Functions::is_enable_social_profiles() || empty($social_profiles) || empty($social_list = Options::get_social_profiles_list())) {
    return;
}
?>
<div class="cldirectory-social-profile-wrap">
    <?php
		$label_text = esc_html__( 'Social:', 'cldirectory' );
		$profile_label       = apply_filters( 'rtcl_social_profile_label', $label_text );
    if($social_list){    
	?>
        <ul class="social-icons">
            <li><?php echo esc_html( $profile_label ); ?></li>
            <?php
                foreach ($social_list as $item => $value) {
                    if (!empty($social_profiles[$item])) {
                        if ( 'twitter' === $item ) {
                            $iconClass = 'fa-brands fa-x-twitter';
                        } else {
                            $iconClass = 'rtcl-icon-' . $item;
                        }
                        ?>
                        <li class="<?php echo esc_attr(strtolower($value)); ?>">
                            <a target="_blank" href="<?php echo esc_url($social_profiles[$item]) ?>" title="<?php echo esc_attr($value) ?>">
                                <i class="rtcl-icon <?php echo esc_attr( $iconClass ); ?>"></i>
                            </a>
                        </li>
                        <?php
                    }
                }
            ?>
        </ul>
    <?php } ?>
</div>