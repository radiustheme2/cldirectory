<?php
/**
 * Old Data Migration (Food Menu data).
 *
 * Handles one-time migration of legacy food menu and logo data
 * to the new format. Shows migration UI only for users upgrading
 * from versions below 2.0.0 and hides after migration is complete.
 *
 * @author  RadiusTheme
 * @since   2.0.0
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Conditionally add migration settings field.
 *
 * Shows migration UI only if:
 * 1. The previous theme version was below 2.0.0 (tracked via option), AND
 * 2. Migration has not yet been completed.
 *
 * @return void
 */
function cldirectory_maybe_add_migration_settings() {
	// If migration is already done, don't show anything.
	if ( get_option( 'rtcl_cldirectory_data_migrated' ) ) {
		return;
	}

	// Track the version when migration was first introduced.
	// This option is set once when the user first loads the updated theme.
	$previous_version = get_option( 'cldirectory_pre_migration_version' );

	if ( false === $previous_version ) {
		// First time running this code — record current version.
		// For existing users upgrading, this captures their pre-update version.
		$current_version = wp_get_theme( get_template() )->get( 'Version' );
		update_option( 'cldirectory_pre_migration_version', $current_version );
		$previous_version = $current_version;
	}

	// Only show migration UI if the user upgraded FROM a version below 2.0.0.
	$migration_threshold = '2.0.0';

	if ( version_compare( $previous_version, $migration_threshold, '<' ) ) {
		add_filter( 'rtcl_general_settings_options', 'cldirectory_add_migration_settings_field', 20 );
	}
}
add_action( 'admin_init', 'cldirectory_maybe_add_migration_settings' );


/**
 * Inject migration Status + Trigger button into RTCL general settings.
 *
 * @param array $options The existing settings options.
 *
 * @return array Modified options with migration fields.
 */
function cldirectory_add_migration_settings_field( $options ) {
	$options['old_data_migration_section'] = [
		'title' => esc_html__( 'Old Data Migration (Food Menu data)', 'cldirectory' ),
		'type'  => 'section',
	];

	// Status HTML.
	$migrated   = get_option( 'rtcl_cldirectory_data_migrated' );
	$status_html = '<div id="cldirectory-migration-status">';

	if ( $migrated ) {
		$status_html .= '<p><strong style="color:green;">'
		                . esc_html__( 'Migration status: DONE', 'cldirectory' )
		                . '</strong></p>';
	} else {
		$status_html .= '<p><strong style="color:#d63638;">'
		                . esc_html__( 'Migration status: Not yet run', 'cldirectory' )
		                . '</strong></p>';
	}

	$status_html .= '</div>';

	$options['cldirectory_migration_status'] = [
		'title' => esc_html__( 'Status', 'cldirectory' ),
		'type'  => 'html',
		'html'  => $status_html,
	];

	// Trigger button HTML.
	ob_start();
	?>
    <div id="cldirectory-migration-wrap" style="margin-top:8px;">
        <form method="post" class="cldirectory-migration-form" style="margin:0;">
			<?php wp_nonce_field( 'cldirectory_migrate_old_data', 'cldirectory_migrate_nonce' ); ?>
            <input type="hidden" name="cldirectory_migrate_action" value="1" />
            <p>
                <button type="submit" class="button button-primary" name="cldirectory_migrate" value="1"
					<?php disabled( (bool) $migrated ); ?>>
					<?php if ( $migrated ) : ?>
						<?php echo esc_html__( 'Already Migrated', 'cldirectory' ); ?>
					<?php else : ?>
						<?php echo esc_html__( 'Run Migration', 'cldirectory' ); ?>
					<?php endif; ?>
                </button>
                <span style="margin-left:10px; color:#666;">
					<?php esc_html_e( 'This runs the one-time old-data migration.', 'cldirectory' ); ?>
				</span>
            </p>
        </form>
    </div>
	<?php
	$button_html = ob_get_clean();

	$options['cldirectory_migration_button'] = [
		'title' => esc_html__( 'Trigger', 'cldirectory' ),
		'type'  => 'html',
		'html'  => $button_html,
	];

	return $options;
}


/**
 * Handle migration form submission on admin POST.
 *
 * Checks capability and nonce, prevents re-run,
 * calls the migration function, and redirects back.
 *
 * @return void
 */
function cldirectory_handle_migration_form_submission() {
	if ( ! is_admin() || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
		return;
	}

	if ( ! isset( $_POST['cldirectory_migrate_action'] ) || '1' !== $_POST['cldirectory_migrate_action'] ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( empty( $_POST['cldirectory_migrate_nonce'] )
	     || ! wp_verify_nonce( $_POST['cldirectory_migrate_nonce'], 'cldirectory_migrate_old_data' )
	) {
		return;
	}

	if ( get_option( 'rtcl_cldirectory_data_migrated' ) ) {
		return;
	}

	if ( function_exists( 'migrate_cldirectory_form_data' ) ) {
		migrate_cldirectory_form_data();
	}

	update_option( 'rtcl_cldirectory_data_migrated', 1 );

	wp_safe_redirect( wp_get_referer() ?: admin_url() );
	exit;
}
add_action( 'admin_init', 'cldirectory_handle_migration_form_submission' );


/**
 * Migrate logo attachment from old meta key to new format.
 *
 * @param int $post_id The listing post ID.
 *
 * @return void
 */
function cldirectory_migrate_logo( $post_id = 0 ) {
	global $wpdb;

	$attachment_id = (int) get_post_meta( $post_id, 'listing_logo_img', true );

	if ( empty( $attachment_id ) || ! is_numeric( $attachment_id ) ) {
		return;
	}

	$new_logo = [ $attachment_id ];
	update_post_meta( $post_id, 'listing_logo', $new_logo );
	update_post_meta( $attachment_id, '_rtcl_attachment_type', 'file' );

	$file = get_post_meta( $attachment_id, '_wp_attached_file', true );

	if ( ! empty( $file ) ) {
		$upload_dir = wp_upload_dir();
		$new_guid   = trailingslashit( $upload_dir['baseurl'] ) . $file;

		$wpdb->update(
			$wpdb->posts,
			[
				'guid'        => $new_guid,
				'post_parent' => $post_id,
			],
			[ 'ID' => $attachment_id ],
			[ '%s', '%d' ],
			[ '%d' ]
		);
	}
}


/**
 * Migrate food menu data from old format to new repeater format.
 *
 * @param int $post_id The listing post ID.
 *
 * @return void
 */
function cldirectory_migrate_food_menu( $post_id = 0 ) {
	global $wpdb;

	$old_data = get_post_meta( $post_id, 'cldirectory_food_list', true );

	if ( empty( $old_data ) || ! is_array( $old_data ) ) {
		return;
	}

	$new_data = [];

	foreach ( $old_data as $group ) {
		$new_data[] = [
			'enable_group_title' => 'yes',
			'menu_name'          => $group['gtitle'] ?? '',
		];

		if ( ! empty( $group['food_list'] ) && is_array( $group['food_list'] ) ) {
			foreach ( $group['food_list'] as $item ) {
				$menu_item = [
					'enable_group_title' => 'no',
					'menu_name'          => $item['title'] ?? '',
					'menu_desc'          => $item['description'] ?? '',
					'menu_price'         => $item['foodprice'] ?? '',
				];

				$attachment_id = isset( $item['attachment_id'] ) ? (int) $item['attachment_id'] : 0;

				if ( ! empty( $attachment_id ) ) {
					$file = get_post_meta( $attachment_id, '_wp_attached_file', true );

					if ( ! empty( $file ) ) {
						update_post_meta( $attachment_id, '_rtcl_attachment_type', 'file' );
						$menu_item['menu_img'] = [ $attachment_id ];

						$upload_dir = wp_upload_dir();
						$new_guid   = trailingslashit( $upload_dir['baseurl'] ) . $file;

						$wpdb->update(
							$wpdb->posts,
							[
								'guid'        => $new_guid,
								'post_parent' => $post_id,
							],
							[ 'ID' => $attachment_id ],
							[ '%s', '%d' ],
							[ '%d' ]
						);
					}
				}

				$new_data[] = $menu_item;
			}
		}
	}

	update_post_meta( $post_id, 'rtcl_cldirectory_food_list', $new_data );
}


/**
 * Run the full migration across all listings.
 *
 * @return void
 */
function migrate_cldirectory_form_data() {
	try {
		$post_ids = get_posts( [
			'post_type'      => 'rtcl_listing',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		] );

		foreach ( $post_ids as $post_id ) {
			cldirectory_migrate_logo( $post_id );
			cldirectory_migrate_food_menu( $post_id );
		}

		update_option( 'rtcl_cldirectory_data_migrated', 1 );
	} catch ( \Throwable $e ) {
		error_log( 'Listing data migration failed: ' . $e->getMessage() );
	}
}