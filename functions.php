<?php
/**
 * The Reporter Child Theme Functions
 */

/**
 * Determine if current request is a Directorist-related frontend page.
 *
 * @return bool
 */
function trc_is_directorist_page() {
    if ( is_admin() ) {
        return false;
    }

    if ( is_singular( 'at_biz_dir' ) || is_post_type_archive( 'at_biz_dir' ) ) {
        return true;
    }

    if ( is_tax( array( 'at_biz_dir-category', 'at_biz_dir-location', 'at_biz_dir-tag' ) ) ) {
        return true;
    }

    // Support custom listing pages powered by Directorist shortcodes.
    if ( is_page( array( 'all-listings-3', 'all-listings', 'directorist' ) ) ) {
        return true;
    }

    $queried_id = get_queried_object_id();
    if ( $queried_id ) {
        $content = get_post_field( 'post_content', $queried_id );
        if ( is_string( $content ) && (
            has_shortcode( $content, 'directorist_all_listing' ) ||
            has_shortcode( $content, 'directorist_search_listing' )
        ) ) {
            return true;
        }
    }

    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
    if ( $request_uri && (
        false !== strpos( $request_uri, '/directory/' ) ||
        false !== strpos( $request_uri, '/directorist/' ) ||
        false !== strpos( $request_uri, '/all-listings-3/' )
    ) ) {
        return true;
    }

    return false;
}

/**
 * Dequeue conflicting parent assets on Directorist pages.
 */
function trc_dequeue_conflicting_parent_assets() {
    if ( ! trc_is_directorist_page() ) {
        return;
    }

    // Parent frontend bundle can override layout rules used by pwdev profile templates.
    wp_dequeue_style( 'the-reporter-frontend' );
    wp_dequeue_style( 'the-reporter-root-style' );
    wp_dequeue_script( 'the-reporter-frontend' );
}
add_action( 'wp_enqueue_scripts', 'trc_dequeue_conflicting_parent_assets', 100 );

/**
 * Load child assets with high priority for Directorist pages.
 */
function trc_enqueue_directorist_child_assets() {
    if ( ! trc_is_directorist_page() ) {
        return;
    }

    $theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
        'the-reporter-child-style',
        get_stylesheet_uri(),
        array(),
        $theme_version
    );

    wp_enqueue_style(
        'the-reporter-child-directorist-style',
        get_stylesheet_directory_uri() . '/assests/css/styles.css',
        array( 'the-reporter-child-style' ),
        $theme_version
    );

    wp_enqueue_script(
        'the-reporter-child-directorist-script',
        get_stylesheet_directory_uri() . '/assests/js/main.js',
        array(),
        $theme_version,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'trc_enqueue_directorist_child_assets', 999 );

/**
 * Add profile body class for scoped styling in Directorist single pages.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function trc_directorist_body_classes( $classes ) {
    if ( trc_is_directorist_page() ) {
        $classes[] = 'pwdev-page-profile';
    }

    return $classes;
}
add_filter( 'body_class', 'trc_directorist_body_classes' );

/**
 * Apply custom meta field filters to Directorist all-listings query.
 * Reads ?custom_field[meta_key][]=value (same format as Directorist's own search query).
 *
 * @param array $args WP_Query arguments.
 * @return array
 */
function trc_apply_custom_meta_filters( $args ) {
    if ( empty( $_GET['custom_field'] ) || ! is_array( $_GET['custom_field'] ) ) {
        return $args;
    }

    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $custom_fields = array_filter( wp_unslash( $_GET['custom_field'] ) );
    $added_queries = array();

    foreach ( $custom_fields as $key => $values ) {
        $key = sanitize_key( $key );
        if ( empty( $key ) ) {
            continue;
        }

        $meta_query = array();

        if ( is_array( $values ) ) {
            $values = array_filter( array_map( 'sanitize_text_field', $values ) );
            if ( empty( $values ) ) {
                continue;
            }
            if ( count( $values ) > 1 ) {
                $sub = array( 'relation' => 'OR' );
                foreach ( $values as $value ) {
                    $sub[] = array(
                        'key'     => '_' . $key,
                        'value'   => $value,
                        'compare' => 'LIKE',
                    );
                }
                $meta_query = $sub;
            } else {
                $meta_query = array(
                    'key'     => '_' . $key,
                    'value'   => reset( $values ),
                    'compare' => 'LIKE',
                );
            }
        } else {
            $value = sanitize_text_field( $values );
            if ( empty( $value ) ) {
                continue;
            }
            // Range format "min-max" for numeric fields like min_deposit
            if ( substr_count( $value, '-' ) === 1 ) {
                $parts = explode( '-', $value, 2 );
                if ( is_numeric( $parts[0] ) && is_numeric( $parts[1] ) ) {
                    $meta_query = array(
                        'key'     => '_' . $key,
                        'value'   => array( (int) $parts[0], (int) $parts[1] ),
                        'type'    => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }
            }
            if ( empty( $meta_query ) ) {
                $meta_query = array(
                    'key'     => '_' . $key,
                    'value'   => $value,
                    'compare' => 'LIKE',
                );
            }
        }

        if ( ! empty( $meta_query ) ) {
            $added_queries[] = $meta_query;
        }
    }

    if ( ! empty( $added_queries ) ) {
        $existing = isset( $args['meta_query'] ) ? (array) $args['meta_query'] : array( 'relation' => 'AND' );
        foreach ( $added_queries as $q ) {
            $existing[] = $q;
        }
        $args['meta_query'] = $existing;
    }

    return $args;
}
// Apply to Directorist's all-listings archive query.
add_filter( 'directorist_all_listings_query_arguments', 'trc_apply_custom_meta_filters' );