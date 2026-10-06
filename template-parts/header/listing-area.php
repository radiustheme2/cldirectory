<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\CLDirectory\RDTheme;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;

$login_icon_title = is_user_logged_in() ? esc_html__( " My Account", 'cldirectory' ) : esc_html__( " Sign in", 'cldirectory' );

if (!empty(RDTheme::$options['header_btn_url'])){
	$button_url = RDTheme::$options['header_btn_url'];
} else {
	if ( class_exists( 'Rtcl' ) ) {
		$button_url = Link::get_listing_form_page_link();
	}
}

?>

<div class="listing-area">
    <div class="header-action">
        <ul class="header-btn">

			<?php if ( class_exists( 'RtclPro' ) && Fns::is_enable_compare() && RDTheme::$options['header_compare_icon'] ):
				$compare_ids = rtcl()->session->get( 'rtcl_compare_ids', [] );
				if ( ! empty( $compare_ids ) || is_array( $compare_ids ) ) {
					$compare_ids = count( $compare_ids );
				}
				?>
                <li class="compare-btn has-count-number button">
                    <a class="listing-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-trigger="hover" title="<?php esc_attr_e( "Compare", "cldirectory" ) ?>" href="<?php echo esc_url( Link::get_page_permalink( 'compare_page' ) ); ?>">
                        <i class="compare-cl-icon"></i>
                    </a>
                    <span class="count rt-compare-count"><?php echo esc_html( $compare_ids ) ?></span>
                </li>
			<?php endif; ?>

			<?php if ( class_exists( 'Rtcl' ) && Functions::is_enable_favourite() && RDTheme::$options['header_fav_icon'] ):
				$favourite_posts = get_user_meta( get_current_user_id(), 'rtcl_favourites', true );
				if ( ! empty( $favourite_posts ) || is_array( $favourite_posts ) ) {
					$favourite_posts = count( $favourite_posts );
				}
				$favourite_posts = $favourite_posts ? $favourite_posts : '0';
				?>
                <li class="favourite has-count-number button">
                    <a class="listing-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-trigger="hover" title="<?php esc_attr_e( "Favourites", "cldirectory" ) ?>" href="<?php echo esc_url( Link::get_my_account_page_link( 'favourites' ) ); ?>">
                        <i class="heart-cl-icon"></i>
                    </a>
                    <span class="count rt-header-favourite-count"><?php echo esc_html( $favourite_posts ) ?></span>
                </li>
			<?php endif; ?>
			
			<?php if ( class_exists( 'Rtcl' ) && RDTheme::$options['header_login_icon'] ):
				?>
                <li class="login-btn button">
                    <a href="<?php echo esc_url( Link::get_my_account_page_link() ); ?>" class="listing-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-trigger="hover" title="<?php esc_attr_e( "Account", "cldirectory" ) ?>">
                        <i class="user-alt-1-cl-icon"></i>
                    </a>
                </li>
			<?php endif; ?>
            <?php
			if ( RDTheme::$options['header_search_icon'] ) {
				?>
                <li class="search-icon button icon-hover-item" >
	                <?php get_template_part( 'template-parts/header/search', 'icon' ); ?>
                </li>
            <?php
			}
			?>
			<?php if ( ( RDTheme::$has_header_btn==1 || RDTheme::$has_header_btn ==='on' ) && RDTheme::$options['header_btn_txt'] ):
				?>
                <li class="header-add-property-btn">
                    <a href="<?php echo esc_url( $button_url ); ?>">
                        <span class="plus">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <rect y="4.00024" width="10" height="2" rx="1"></rect>
                                <rect x="4" y="10.0002" width="10" height="2" rx="1" transform="rotate(-90 4 10.0002)"></rect>
                            </svg>
                        </span>
                        <span class="text"><?php echo esc_html(RDTheme::$options['header_btn_txt']); ?></span>
                    </a>
                </li>
			<?php endif; ?>
            <li class="offcanvar_bar button">
                <span class="sidebarBtn ">
                    <span class="fa fa-bars">
                    </span>
                </span>
            </li>

        </ul>
    </div>
</div>
