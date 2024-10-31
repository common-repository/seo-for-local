<?php

if ( !function_exists( 'wpt_mlsl_locations_init' ) ) {
    function wpt_mlsl_get_locations_count()
    {
        $cache_key = 'wpt_mlsl_locations_count';
        $counts = wp_cache_get( $cache_key, 'wpt_mlsl' );
        if ( false !== $counts ) {
            return $counts;
        }
        global  $wpdb ;
        // phpcs:ignore
        $results = (array) $wpdb->get_results( $wpdb->prepare( "SELECT COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s", 'wpt-mlsl-locations' ), ARRAY_A );
        $counts = ( isset( $results[0], $results[0]['num_posts'] ) ? $results[0]['num_posts'] : 0 );
        wp_cache_set( $cache_key, $counts, 'wpt_mlsl' );
        return $counts;
    }

}
if ( !function_exists( 'wpt_mlsl_locations_init' ) ) {
    /**
     * Registers the `wpt_mlsl_locations` post type.
     */
    function wpt_mlsl_locations_init()
    {
        $slug = get_option( "_wpt_mlsl_settings_locations_slug", 'location' );
        $singular = get_option( "_wpt_mlsl_settings_locations_label_singular", 'Location' );
        $plural = get_option( "_wpt_mlsl_settings_locations_label_plural", 'Locations' );
        $private = get_option( '_wpt_mlsl_settings_make_locations_private', false );
        $args = [
            'labels'                => [
            'name'                  => __( "{$plural}", 'seo-for-local' ),
            'singular_name'         => __( "{$singular}", 'seo-for-local' ),
            'all_items'             => __( "All {$plural}", 'seo-for-local' ),
            'archives'              => __( "{$singular} Archives", 'seo-for-local' ),
            'attributes'            => __( "{$singular} Attributes", 'seo-for-local' ),
            'insert_into_item'      => __( "Insert into {$singular}", 'seo-for-local' ),
            'uploaded_to_this_item' => __( "Uploaded to this {$singular}", 'seo-for-local' ),
            'featured_image'        => _x( 'Featured Image', 'wpt-mlsl-locations', 'seo-for-local' ),
            'set_featured_image'    => _x( 'Set featured image', 'wpt-mlsl-locations', 'seo-for-local' ),
            'remove_featured_image' => _x( 'Remove featured image', 'wpt-mlsl-locations', 'seo-for-local' ),
            'use_featured_image'    => _x( 'Use as featured image', 'wpt-mlsl-locations', 'seo-for-local' ),
            'filter_items_list'     => __( "Filter {$plural} list", 'seo-for-local' ),
            'items_list_navigation' => __( "{$plural} list navigation", 'seo-for-local' ),
            'items_list'            => __( "{$plural} list", 'seo-for-local' ),
            'new_item'              => __( "New {$singular}", 'seo-for-local' ),
            'add_new'               => __( "Add New", 'seo-for-local' ),
            'add_new_item'          => __( "Add New {$singular}", 'seo-for-local' ),
            'edit_item'             => __( "Edit {$singular}", 'seo-for-local' ),
            'view_item'             => __( "View {$singular}", 'seo-for-local' ),
            'view_items'            => __( "View {$plural}", 'seo-for-local' ),
            'search_items'          => __( "Search {$plural}", 'seo-for-local' ),
            'not_found'             => __( "No {$plural} found", 'seo-for-local' ),
            'not_found_in_trash'    => __( "No {$plural} found in trash", 'seo-for-local' ),
            'parent_item_colon'     => __( "Parent {$singular}:", 'seo-for-local' ),
            'menu_name'             => __( "{$plural}", 'seo-for-local' ),
        ],
            'public'                => $private != 'yes',
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            "supports"              => [
            "title",
            "editor",
            "excerpt",
            "page-attributes",
            "revisions",
            "thumbnail"
        ],
            'has_archive'           => true,
            "rewrite"               => [
            'slug' => $slug,
        ],
            'query_var'             => true,
            'menu_position'         => null,
            'menu_icon'             => 'dashicons-location',
            'show_in_rest'          => $private != 'yes',
            'rest_base'             => 'wpt-mlsl-locations',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'capabilities'          => [
            'create_posts' => ( wpt_mlsl_get_locations_count() >= 1 ? false : true ),
        ],
            'map_meta_cap'          => true,
        ];
        register_post_type( 'wpt-mlsl-locations', $args );
    }

}
add_action( 'init', 'wpt_mlsl_locations_init' );
if ( !function_exists( 'wpt_mlsl_locations_updated_messages' ) ) {
    function wpt_mlsl_locations_updated_messages( $messages )
    {
        $singular = get_option( "_wpt_schema_local_business_settings_locations_label_singular", true );
        if ( !$singular ) {
            $singular = "Location";
        }
        global  $post ;
        $permalink = get_permalink( $post );
        $messages['wpt-mlsl-locations'] = [
            0  => '',
            1  => sprintf(
            __( '%s updated. <a target="_blank" href="%s">View %s</a>', 'seo-for-local' ),
            $singular,
            esc_url( $permalink ),
            $singular
        ),
            2  => __( 'Custom field updated.', 'seo-for-local' ),
            3  => __( 'Custom field deleted.', 'seo-for-local' ),
            4  => __( "{$singular} updated.", 'seo-for-local' ),
            5  => ( isset( $_GET['revision'] ) ? sprintf( __( '%s restored to revision from %s', 'seo-for-local' ), $singular, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false ),
            6  => sprintf( __( '%s published. <a href="%s">View Location</a>', 'seo-for-local' ), $singular, esc_url( $permalink ) ),
            7  => __( "{$singular} saved.", 'seo-for-local' ),
            8  => sprintf(
            __( '%s submitted. <a target="_blank" href="%s">Preview %s</a>', 'seo-for-local' ),
            $singular,
            esc_url( add_query_arg( 'preview', 'true', $permalink ) ),
            $singular
        ),
            9  => sprintf(
            __( '%s scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview %s</a>', 'seo-for-local' ),
            $singular,
            date_i18n( __( 'M j, Y @ G:i', 'seo-for-local' ), strtotime( $post->post_date ) ),
            esc_url( $permalink ),
            $singular
        ),
            10 => sprintf(
            __( '%s draft updated. <a target="_blank" href="%s">Preview %s</a>', 'seo-for-local' ),
            $singular,
            esc_url( add_query_arg( 'preview', 'true', $permalink ) ),
            $singular
        ),
        ];
        return $messages;
    }

}
add_filter( 'post_updated_messages', 'wpt_mlsl_locations_updated_messages' );
if ( !function_exists( 'wpt_mlsl_locations_bulk_updated_messages' ) ) {
    function wpt_mlsl_locations_bulk_updated_messages( $bulk_messages, $bulk_counts )
    {
        $singular = get_option( "_wpt_schema_local_business_settings_locations_label_singular", true );
        if ( !$singular ) {
            $singular = "Location";
        }
        $plural = get_option( "_wpt_mlsl_settings_locations_label_plural", true );
        if ( !$plural ) {
            $plural = "Locations";
        }
        global  $post ;
        $bulk_messages['wpt-mlsl-locations'] = [
            'updated'   => _n(
            "%s {$singular} updated.",
            "%s {$plural} updated.",
            $bulk_counts['updated'],
            'seo-for-local'
        ),
            'locked'    => ( 1 === $bulk_counts['locked'] ? __( "1 {$singular} not updated, somebody is editing it.", 'seo-for-local' ) : _n(
            "%s {$singular} not updated, somebody is editing it.",
            "%s {$plural} not updated, somebody is editing them.",
            $bulk_counts['locked'],
            'seo-for-local'
        ) ),
            'deleted'   => _n(
            "%s {$singular} permanently deleted.",
            "%s {$plural} permanently deleted.",
            $bulk_counts['deleted'],
            'seo-for-local'
        ),
            'trashed'   => _n(
            "%s {$singular} moved to the Trash.",
            "%s {$plural} moved to the Trash.",
            $bulk_counts['trashed'],
            'seo-for-local'
        ),
            'untrashed' => _n(
            "%s {$singular} restored from the Trash.",
            "%s {$plural} restored from the Trash.",
            $bulk_counts['untrashed'],
            'seo-for-local'
        ),
        ];
        return $bulk_messages;
    }

}
add_filter(
    'bulk_post_updated_messages',
    'wpt_mlsl_locations_bulk_updated_messages',
    10,
    2
);