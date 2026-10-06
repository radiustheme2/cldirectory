<?php
/**
 * c-fields.php
 * Render all custom field sections EXCEPT specified ones, and respect section columns.
 *
 * Replace your existing c-fields.php with this file.
 *
 * @var $form Form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

if ( ! is_a( $form, Form::class ) ) {
	return;
}

$listing_id = get_the_ID();

// Sections to exclude by exact section id
$exclude_section_ids = [
	'food_menu',
];

// We'll collect rendered HTML into $fieldData like original template
$fieldData = '';

// Get sections from form
$sections = [];
if ( method_exists( $form, 'getSections' ) ) {
	$sections = $form->getSections();
} elseif ( isset( $form->sections ) ) {
	$sections = $form->sections;
}

if ( is_array( $sections ) && count( $sections ) ) {

	ob_start();

	foreach ( $sections as $section ) {

		// Normalise section id
		$section_id = '';
		if ( is_array( $section ) && isset( $section['id'] ) ) {
			$section_id = (string) $section['id'];
		} elseif ( is_object( $section ) && isset( $section->id ) ) {
			$section_id = (string) $section->id;
		}

		// If this section is in exclude list, skip it entirely
		if ( $section_id !== '' && in_array( $section_id, $exclude_section_ids, true ) ) {
			continue;
		}

		// Get section title
		$section_title = '';
		if ( is_array( $section ) && isset( $section['title'] ) ) {
			$section_title = (string) $section['title'];
		} elseif ( is_object( $section ) && isset( $section->title ) ) {
			$section_title = (string) $section->title;
		}

		// Get section container class
		$section_container_class = '';
		if ( is_array( $section ) && isset( $section['container_class'] ) ) {
			$section_container_class = (string) $section['container_class'];
		} elseif ( is_object( $section ) && isset( $section->container_class ) ) {
			$section_container_class = (string) $section->container_class;
		}

		// containers (formerly columns) -> each container/column has 'fields' => [ uuids ]
		$columns = [];
		if ( is_array( $section ) ) {
			if ( isset( $section['containers'] ) ) {
				foreach ( $section['containers'] as $container ) {
					$container = is_object( $container ) ? (array) $container : $container;
					if ( is_array( $container ) && isset( $container['columns'] ) ) {
						// nested: containers > columns > fields
						foreach ( (array) $container['columns'] as $col ) {
							$columns[] = $col;
						}
					} else {
						// flat: containers used like old columns
						$columns[] = $container;
					}
				}
			} elseif ( isset( $section['columns'] ) ) {
				$columns = $section['columns'];
			}
		} elseif ( is_object( $section ) ) {
			if ( isset( $section->containers ) ) {
				foreach ( (array) $section->containers as $container ) {
					$container = is_object( $container ) ? (array) $container : $container;
					if ( is_array( $container ) && isset( $container['columns'] ) ) {
						foreach ( (array) $container['columns'] as $col ) {
							$columns[] = $col;
						}
					} else {
						$columns[] = $container;
					}
				}
			} elseif ( isset( $section->columns ) ) {
				$columns = (array) $section->columns;
			}
		}

		if ( empty( $columns ) || ! is_array( $columns ) ) {
			continue;
		}

		$section_columns = count( $columns );

		// We'll create per-column HTML pieces
		$col_items_html = array_fill( 0, $section_columns, '' );

		// iterate columns and fields
		foreach ( $columns as $col_index => $col ) {
			$col_fields = [];
			if ( is_array( $col ) && isset( $col['fields'] ) ) {
				$col_fields = (array) $col['fields'];
			} elseif ( is_object( $col ) && isset( $col->fields ) ) {
				$col_fields = (array) $col->fields;
			}

			if ( empty( $col_fields ) ) {
				continue;
			}

			// iterate each field UUID and render using FBField
			foreach ( $col_fields as $field_uuid ) {
				if ( empty( $field_uuid ) ) {
					continue;
				}

				// Try to get the field definition by UUID (form helper)
				$field_def = null;
				if ( method_exists( $form, 'getFieldByUuid' ) ) {
					$field_def = $form->getFieldByUuid( $field_uuid );
				} else {
					// fallback: try get all fields and match uuid (less efficient)
					$all_fields = method_exists( $form, 'getFields' ) ? $form->getFields() : (isset($form->fields) ? $form->fields : []);
					if ( is_array( $all_fields ) ) {
						foreach ( $all_fields as $af ) {
							if ( is_array( $af ) && isset( $af['uuid'] ) && (string) $af['uuid'] === (string) $field_uuid ) {
								$field_def = $af;
								break;
							} elseif ( is_object( $af ) && isset( $af->uuid ) && (string) $af->uuid === (string) $field_uuid ) {
								$field_def = $af;
								break;
							}
						}
					}
				}

				if ( empty( $field_def ) ) {
					// couldn't find definition — skip
					continue;
				}

				// instantiate FBField
				$field = new FBField( $field_def );

				// skip if not single viewable
				if ( ! $field || ! $field->isSingleViewAble() ) {
					continue;
				}

				// get formatted value
				$value = $field->getFormattedCustomFieldValue( $listing_id );

				if ( empty( $value ) ) {
					continue;
				}

				// render markup for this field
				$icon = $field->getIconData();

				ob_start();
				?>
                <div class="rtcl-cfp-item rtcl-cfp-<?php echo esc_attr( $field->getElement() ); ?>" data-name="<?php echo esc_attr( $field->getName() ); ?>"
                     data-uuid="<?php echo esc_attr( $field->getUuid() ); ?>">
					<?php
					if ( $field->getElement() === 'url' ) {
						$nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : '';
						?>
                        <a href="<?php echo esc_url( $value ); ?>" target="<?php echo esc_attr( $field->getTarget() ); ?>"<?php echo esc_html( $nofollow ); ?>><?php echo esc_html( $field->getLabel() ); ?></a>
						<?php
					} else {
						if ( 'repeater' === $field->getElement() ) {
							if ( ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) || ! empty( $field->getLabel() ) ) {
								if ( 'repeater' === $field->getElement() ) {
									$fieldLabelClass = 'rtcl-cfp-repeater-label-wrap';
								} else {
									$fieldLabelClass = 'rtcl-cfp-label-wrap';
								}
								?>
                                <div class="<?php echo esc_attr( $fieldLabelClass ); ?>">
									<?php
									if ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) {
										?>
                                        <div class="rtcl-field-icon"><i class="<?php echo esc_attr( $icon['class'] ); ?>"></i></div>
										<?php
									}
									if ( ! empty( $field->getLabel() ) ) {
										?>
                                        <div class='cfp-label'><?php echo esc_html( $field->getLabel() ); ?></div>
										<?php
									}
									?>
                                </div>
							<?php } ?>
                            <div class="cfp-value">
								<?php
								if ( 'repeater' === $field->getElement() ) {
									$repeaterFields = $field->getData( 'fields', [] );
									if ( ! empty( $repeaterFields ) && is_array( $value ) ) {
										?>
                                        <div class="rtcl-cfp-repeater-items">
											<?php
											foreach ( $value as $rValueIndex => $rValues ) {
												?>
                                                <div class="rtcl-cfp-repeater-item">
													<?php
													foreach ( $repeaterFields as $repeaterField ) {
														$rField = new FBField( $repeaterField );
														$rValue = 'file' === $rField->getElement() ? ( ! empty( $rValues[ $rField->getName() ] )
														                                               && is_array( $rValues[ $rField->getName() ] )
															? FBHelper::getFieldAttachmentFiles( $listing_id, $rField->getField(), $rValues[ $rField->getName() ], true )
															: [] ) : ( $rValues[ $rField->getName() ] ?? '' );
														?>
                                                        <div class="rtcl-cfp-repeater-field" data-name="<?php echo esc_attr( $field->getName() ); ?>"
                                                             data-uuid="<?php echo esc_attr( $field->getUuid() ); ?>">
															<?php
															$rIcon = $rField->getIconData();
															if ( ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) )
															     || ! empty( $rField->getLabel() )
															) {
																?>
                                                                <div class="rtcl-cfp-label-wrap">
																	<?php
																	if ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) ) {
																		?>
                                                                        <div class="rtcl-field-icon"><i
                                                                                    class="<?php echo esc_attr( $rIcon['class'] ); ?>"></i>
                                                                        </div>
																		<?php
																	}
																	if ( ! empty( $rField->getLabel() ) ) {
																		?>
                                                                        <div
                                                                                class='cfp-label'><?php echo esc_html( $rField->getLabel() ); ?></div>
																		<?php
																	}
																	?>
                                                                </div>
															<?php } ?>
                                                            <div class="cfp-value">
																<?php Functions::print_html( FBHelper::getFormattedFieldHtml( $rValue, $rField ) ); ?>
                                                            </div>
                                                        </div>
														<?php
													}
													?>
                                                </div>
												<?php
											}
											?>
                                        </div>
										<?php
									}
								} else {
									Functions::print_html( FBHelper::getFormattedFieldHtml( $value, $field ) );
								}
								?>
                            </div>
                        <?php } else {
                            if ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) {
                                    ?>
                                    <div class="rtcl-field-icon"><i class="<?php echo esc_attr( $icon['class'] ); ?>"></i></div>
                                    <?php
                                }
                            ?>
                            <div class="label-and-value-separator">
                                <?php if ( ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) || ! empty( $field->getLabel() ) ) {
                                    if ( 'repeater' === $field->getElement() ) {
                                        $fieldLabelClass = 'rtcl-cfp-repeater-label-wrap';
                                    } else {
                                        $fieldLabelClass = 'rtcl-cfp-label-wrap';
                                    }
                                    ?>
                                    <div class="<?php echo esc_attr( $fieldLabelClass ); ?>">
                                        <?php

                                        if ( ! empty( $field->getLabel() ) ) {
                                            ?>
                                            <div class='cfp-label'><?php echo esc_html( $field->getLabel() ); ?></div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                <?php } ?>

                                <div class="cfp-value">
                                    <?php Functions::print_html( FBHelper::getFormattedFieldHtml( $value, $field ) ); ?>
                                </div>
                            </div>
                        <?php } ?>
					<?php } ?>
                </div>
				<?php
				$field_html = ob_get_clean();

				// Append to this column's HTML
				$col_items_html[ $col_index ] .= $field_html;

			} // end foreach field_uuid
		} // end foreach columns

		// If any column has items, render section box with columns
		$has_any = false;
		foreach ( $col_items_html as $chtml ) {
			if ( trim( $chtml ) !== '' ) {
				$has_any = true;
				break;
			}
		}

		if ( $has_any ) {
			$section_name_class    = sanitize_title( $section_title );
			$section_wrapper_class = trim( $section_container_class . ' ' . $section_name_class );
			?>

            <div class="cldirectory-accordion-item" id="listing-fb-custom-feilds-<?php echo esc_attr( $section_id ); ?>">
			    <?php if ( $section_title ) : ?>
                    <div class="accordion-header" id="clproperty_listing_fb_custom_fields_heading">
                        <h2 class="mb-0">
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_fb_<?php echo esc_attr( $section_wrapper_class ); ?>_custom_fields" aria-expanded="true" aria-controls="clproperty_listing_fb_<?php echo esc_attr( $section_wrapper_class ); ?>_custom_fields">
                                <?php echo esc_html( $section_title ); ?>
                            </button>
                        </h2>
                    </div>
			    <?php endif; ?>
                <div id="clproperty_listing_fb_<?php echo esc_attr( $section_wrapper_class ); ?>_custom_fields" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_fb_custom_fields_heading">
                    <div class="cldirectory-accordion-content">
	                    <?php
	                        // Output each column
                            foreach ( $col_items_html as $ci => $ci_html ) {
                                $col_index_num = $ci + 1;
                                $col_class      = 'section-column column-' . $col_index_num;
                                ?>
                                <div class="<?php echo esc_attr( $col_class ); ?>">
                                    <?php echo wp_kses_post( $ci_html ); ?>
                                </div>
                                <?php
                            }
	                    ?>
                    </div>
                </div>
            </div>
			<?php
		}

	} // end foreach sections

	$fieldData = ob_get_clean();

	// final wrapping similar to original template
	if ( $fieldData ) :
		?>
        <div class="rtcl-single-custom-fields">
            <div class="rtcl-cf-properties">
				<?php Functions::print_html( $fieldData, true ); ?>
            </div>
        </div>
	<?php
	endif;
}
