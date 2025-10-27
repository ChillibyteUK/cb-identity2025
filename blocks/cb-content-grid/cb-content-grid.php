<?php
/**
 * CB Content Grid Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get grid rows from block data.
$grid_rows = $block['data']['grid_rows'] ?? array();
if ( empty( $grid_rows ) ) {
    return;
}
?>
<section class="cb-content-grid id-container py-5">
    <?php
    foreach ( $grid_rows as $row_index => $layout ) {
        // Support both 'multi-module_row' and 'multi_module_row' for compatibility
        if ( 'single_module_row' === $layout ) { // --- Single module row ---
            $mtype      = $block['data'][ 'grid_rows_' . $row_index . '_module_type' ] ?? '';
            $width_raw  = $block['data'][ 'grid_rows_' . $row_index . '_column_width' ] ?? 12;
            $offset_raw = $block['data'][ 'grid_rows_' . $row_index . '_column_offset' ] ?? 0;
            $width      = intval( $width_raw );
            $offset     = intval( $offset_raw );

            if ( $width < 1 || $width > 12 ) {
                $width = 12;
            }
            if ( $offset < 0 ) {
                $offset = 0;
            }
            if ( $offset > 11 ) {
                $offset = 11;
            }
            if ( $width + $offset > 12 ) {
                $offset = max( 0, 12 - $width );
            }
            $class = trim( $block['data'][ 'grid_rows_' . $row_index . '_custom_class' ] ?? '' );

            $col_classes = array(
                'col-md-' . esc_attr( $width ),
            );
            if ( $offset && '0' !== $offset ) {
                $col_classes[] = 'offset-md-' . esc_attr( $offset );
            }
            if ( $class ) {
                $col_classes[] = sanitize_html_class( $class );
            }
            ?>
            <div class="row gx-4 gy-5 mb-4">
                <div class="<?= esc_attr( implode( ' ', $col_classes ) ); ?>">
                    <?php
                    if ( 'image' === $mtype || 'Image' === $mtype ) {
                        $image_id = $block['data'][ 'grid_rows_' . $row_index . '_image' ] ?? null;
                        $caption  = $block['data'][ 'grid_rows_' . $row_index . '_caption' ] ?? '';
                        if ( $image_id ) {
                            echo wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'img-fluid' ) );
                        }
                        if ( $caption ) {
                            echo '<p class="image-caption small text-muted mt-2">' . esc_html( $caption ) . '</p>';
                        }
                    } elseif ( 'text' === $mtype || 'Text' === $mtype ) {
                        $text = $block['data'][ 'grid_rows_' . $row_index . '_text' ] ?? '';
                        echo wp_kses_post( $text );
                    } elseif ( 'video' === $mtype || 'Video' === $mtype ) {
                        $vimeo_url = $block['data'][ 'grid_rows_' . $row_index . '_vimeo_url' ] ?? '';
                        if ( $vimeo_url ) {
                            echo '<div class="ratio ratio-16x9">';
							?>
							<iframe src="<?= esc_url( $vimeo_url ); ?>" frameborder="0" allow="fullscreen" allowfullscreen></iframe>
							<?php
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        	<?php
        } elseif ( 'multi_module_row' === $layout || 'multi-module_row' === $layout ) { // --- Multi-module row ---
            // Try to determine module count by looking for the highest module index
            $modules_count = 0;
            // Prefer 'grid_rows_X_module' (as in your data), fallback to 'grid_rows_X_modules'
            if ( isset($block['data'][ 'grid_rows_' . $row_index . '_module' ]) ) {
                $modules_count = intval($block['data'][ 'grid_rows_' . $row_index . '_module' ]);
            } elseif ( isset($block['data'][ 'grid_rows_' . $row_index . '_modules' ]) ) {
                $modules_count = intval($block['data'][ 'grid_rows_' . $row_index . '_modules' ]);
            } else {
                // Try to count by scanning keys
                foreach ($block['data'] as $k => $v) {
                    if (strpos($k, 'grid_rows_' . $row_index . '_module_') === 0 && strpos($k, '_module_type') !== false) {
                        $modules_count++;
                    }
                }
            }
            if ( $modules_count > 0 ) {
                ?>
                <div class="row gx-4 gy-5 mb-4">
                    <?php
                    for ( $module_index = 0; $module_index < $modules_count; $module_index++ ) {
                        $mtype      = $block['data'][ 'grid_rows_' . $row_index . '_module_' . $module_index . '_module_type' ] ?? '';
                        $width_raw  = $block['data'][ 'grid_rows_' . $row_index . '_module_' . $module_index . '_column_width' ] ?? 12;
                        $offset_raw = $block['data'][ 'grid_rows_' . $row_index . '_module_' . $module_index . '_column_offset' ] ?? 0;
                        $width      = intval( $width_raw );
                        $offset     = intval( $offset_raw );

                        if ( $width < 1 || $width > 12 ) {
                            $width = 12;
                        }
                        if ( $offset < 0 ) {
                            $offset = 0;
                        }
                        if ( $offset > 11 ) {
                            $offset = 11;
                        }
                        if ( $width + $offset > 12 ) {
                            $offset = max( 0, 12 - $width );
                        }
                        $class = trim( $block['data'][ 'grid_rows_' . $row_index . '_module_' . $module_index . '_custom_class' ] ?? '' );

                        $col_classes = array(
                            'col-md-' . esc_attr( $width ),
                        );
                        if ( $offset && '0' !== $offset ) {
                            $col_classes[] = 'offset-md-' . esc_attr( $offset );
                        }
                        if ( $class ) {
                            $col_classes[] = sanitize_html_class( $class );
                        }
                        ?>
                        <div class="<?= esc_attr( implode( ' ', $col_classes ) ); ?>">
                            <?php
                            if ( 'image' === $mtype || 'Image' === $mtype ) {
                                $image_id = $block['data'][ 'grid_rows_' . $row_index . '_module_' . $module_index . '_image' ] ?? null;
                                $caption  = $block['data'][ 'grid_rows_' . $row_index . '_module_' . $module_index . '_caption' ] ?? '';
                                if ( $image_id ) {
                                    echo wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'img-fluid' ) );
                                }
                                if ( $caption ) {
                                    echo '<p class="image-caption small text-muted mt-2">' . esc_html( $caption ) . '</p>';
                                }
                            } elseif ( 'text' === $mtype || 'Text' === $mtype ) {
                                $text = $block['data'][ 'grid_rows_' . $row_index . '_module_' . $module_index . '_text' ] ?? '';
                                echo wp_kses_post( $text );
                            } elseif ( 'video' === $mtype || 'Video' === $mtype ) {
                                $vimeo_url = $block['data'][ 'grid_rows_' . $row_index . '_module_' . $module_index . '_vimeo_url' ] ?? '';
                                if ( $vimeo_url ) {
                                    echo '<div class="ratio ratio-16x9">';
                                    echo wp_kses_post( wp_oembed_get( esc_url( $vimeo_url ) ) );
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            <?php
            } else {
                echo '<!-- No modules found for row ' . esc_html($row_index) . ' -->';
            }
        }
    }
    ?>
</section>
