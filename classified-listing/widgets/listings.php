<?php
/**
 * @var WP_Query $rtcl_query
 * @var array    $instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;

?>
<div class="rtcl rtcl-widget-listings <?php echo esc_attr($instance['image_position']); ?>">
	<div class="<?php echo esc_attr($instance['wrapper_classes']); ?>"
		 data-options="<?php echo !empty($instance['slider_options']) ? esc_attr(wp_json_encode($instance['slider_options'])) : ''; ?>">
		<?php

		$listing_item_class = ['rtcl-widget-listing-item', 'listing-item'];
		if ($instance['show_image'] && $instance['image_position'] == "left") {
			$listing_item_class[] = 'rtcl-flex';
		}
		if ('slider' === $instance['view']) {
			$listing_item_class[] = 'swiper-slide';
			echo '<div class="swiper-wrapper">';
		}
		while ($rtcl_query->have_posts()):
			$rtcl_query->the_post();
			$listing = rtcl()->factory->get_listing(get_the_ID());
			$listing_meta = $img = $labels = $uInfo = $time = $location = $category = $price = null;
			?>
			<div <?php Functions::listing_class($listing_item_class); ?>>
				<?php
				if ($instance['show_image']) {
					$img = sprintf("<div class='listing-thumb'><a href='%s' title='%s'>%s</a></div>",
						get_the_permalink(),
						esc_html(get_the_title()),
						$listing->get_the_thumbnail('thumbnail')
					);
				}
				if ($instance['show_labels']) {
					$labels = $listing->badges();
				}
				if ($instance['show_date']) {
					$time = sprintf('<li class="date"><i class="rtcl-icon rtcl-icon-clock" aria-hidden="true"></i>%s</li>',
						$listing->get_the_time()
					);
				}
				if ($instance['show_location']) {
					$location = sprintf('<li class="location"><i class="map-marker-cl-icon" aria-hidden="true"></i>%s</li>',
						$listing->the_locations(false)
					);
				}
				if ($instance['show_category']) {
					$category = sprintf('<li class="category"><i class="tag-cl-icon" aria-hidden="true"></i>%s</li>',
						$listing->the_categories(false)
					);
				}
				if ($instance['show_price'] && $price_html = $listing->get_price_html()) {
					$price = sprintf('<div class="listing-price">%s</div>', $price_html);
				}
				$author_html = '';
				if ($instance['show_user']) {
					$author_html = sprintf('<span><i class="user-alt-cl-icon" aria-hidden="true"></i>%s</span>', get_the_author());
				}
				$views_html = '';
				if ($instance['show_views']) {
					$views = absint(get_post_meta(get_the_ID(), '_views', true));
					$views_html = sprintf('<span><i class="eye-cl-icon" aria-hidden="true"></i>%s</span>',
						// translators: %s: number of views.
					sprintf(_n("%s view", "%s views", $views, 'cldirectory'), number_format_i18n($views))
					);
				}
				if ($author_html || $views_html) {
					$uInfo = sprintf('<li class="info">%s</li>',
						$author_html . $views_html
					);
				}

				if ($uInfo || $time || $category || $location) {
					$listing_meta = sprintf('<ul class="listing-meta">%s%s%s%s</ul>', $uInfo, $time, $category,
						$location);
				}

				$title = sprintf('<h3 class="listing-title rtcl-listing-title"><a href="%1$s" title="%2$s">%2$s</a></h3>',
					get_the_permalink(),
					esc_html(get_the_title())
				);

				$item_content = sprintf('<div class="item-content">%s %s %s %s</div>',
					$labels,
					$title,
					$listing_meta,
					$price);
				echo wp_kses_post( $img . $item_content );
				?>

			</div>
		<?php
		endwhile;
		wp_reset_postdata();
		if ('slider' === $instance['view']) {
			echo '</div>
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>';

		}
		?>
	</div>
	<?php if ($instance['pagination'] && in_array($instance['view'], ['grid', 'list'])) {
		Pagination::pagination($rtcl_query);
	} ?>
</div>