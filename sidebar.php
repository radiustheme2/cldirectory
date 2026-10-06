<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php
if ( RDTheme::$sidebar && is_active_sidebar( RDTheme::$sidebar ) ) {
	$cldirectory_sidebar = RDTheme::$sidebar;
} elseif ( is_active_sidebar( 'sidebar' ) ) {
	$cldirectory_sidebar = 'sidebar';
} else {
	$cldirectory_sidebar = '';
}
// The Classified Listing Ajax Filter has its own "Show Filter" panel on phones, where the theme toggle is hidden.
$cldirectory_has_ajax_filter = $cldirectory_sidebar && is_active_widget( false, false, 'rtcl-widget-ajax-filter' ) === $cldirectory_sidebar;
$cldirectory_sidebar_class   = $cldirectory_has_ajax_filter ? ' has-ajax-filter' : '';
?>
<div id="sticky_sidebar" class="<?php Helper::the_sidebar_class(); ?>">
    <?php if ( is_post_type_archive( 'rtcl_listing' ) ) { ?>
        <a id="cldirectory-toggle-sidebar" class="<?php echo esc_attr( trim( $cldirectory_sidebar_class ) ); ?>" href="#"><?php esc_html_e( 'Toggle Filter', 'cldirectory' ); ?></a>
    <?php } ?>
	<?php
	if ( RDTheme::$sidebar && is_active_sidebar( RDTheme::$sidebar ) ) { ?>
        <aside class="sidebar-widget main-sidebar-wrapper<?php echo esc_attr( $cldirectory_sidebar_class ); ?>">
            <?php dynamic_sidebar( RDTheme::$sidebar ); ?>
        </aside>
    <?php
	} elseif( is_active_sidebar( 'sidebar' ) ) { ?>
        <aside class="sidebar-widget main-sidebar-wrapper sidebar__inner<?php echo esc_attr( $cldirectory_sidebar_class ); ?>">
			<?php dynamic_sidebar( 'sidebar' ); ?>
        </aside>
		<?php
	}
	?>
</div>