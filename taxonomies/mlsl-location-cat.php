<?php

if (!function_exists('mlsl_location_cat_init')) {
    function mlsl_location_cat_init()
    {
        $slug     = get_option("_wpt_mlsl_settings_locations_cat_slug", 'location-categories');
        $singular = get_option("_wpt_mlsl_settings_locations_cat_label_singular", 'Location Category');
        $plural   = get_option("_wpt_mlsl_settings_locations_cat_label_plural", 'Location Categories');
        $private  = get_option('_wpt_mlsl_settings_make_locations_private', false);

        register_taxonomy('mlsl-location-cat', ['wpt-mlsl-locations'], [
            'hierarchical'          => false,
            'public'                => !$private,
            'show_in_nav_menus'     => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => !$private,
            'capabilities'          => [
                'manage_terms' => 'edit_posts',
                'edit_terms'   => 'edit_posts',
                'delete_terms' => 'edit_posts',
                'assign_terms' => 'edit_posts',
            ],
            'labels'                => [
                'name'                       => __("{$plural}", 'seo-for-local'),
                'singular_name'              => __("{$singular}', 'taxonomy general name", 'seo-for-local'),
                'search_items'               => __("Search {$plural}", 'seo-for-local'),
                'popular_items'              => __("Popular {$plural}", 'seo-for-local'),
                'all_items'                  => __("All {$plural}", 'seo-for-local'),
                'parent_item'                => __("Parent {$singular}", 'seo-for-local'),
                'parent_item_colon'          => __("Parent {$singular}:", 'seo-for-local'),
                'edit_item'                  => __("Edit {$singular}", 'seo-for-local'),
                'update_item'                => __("Update {$singular}", 'seo-for-local'),
                'view_item'                  => __("View {$singular}", 'seo-for-local'),
                'add_new_item'               => __("Add New {$singular}", 'seo-for-local'),
                'new_item_name'              => __("New {$singular}", 'seo-for-local'),
                'separate_items_with_commas' => __("Separate {$plural} with commas", 'seo-for-local'),
                'add_or_remove_items'        => __("Add or remove {$plural}", 'seo-for-local'),
                'choose_from_most_used'      => __("Choose from the most used {$plural}", 'seo-for-local'),
                'not_found'                  => __("No {$plural} found.", 'seo-for-local'),
                'no_terms'                   => __("No {$plural}", 'seo-for-local'),
                'menu_name'                  => __("{$plural}", 'seo-for-local'),
                'items_list_navigation'      => __("{$plural} list navigation", 'seo-for-local'),
                'items_list'                 => __("{$plural} list", 'seo-for-local'),
                'most_used'                  => __("Most Used', 'mlsl-location-cat", 'seo-for-local'),
                'back_to_items'              => __("&larr; Back to {$plural}", 'seo-for-local'),
            ],
            'show_in_rest'          => !$private,
            "rewrite"               => ['slug' => $slug],
            'rest_base'             => 'mlsl-location-cat',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ]);
    }
}

add_action('init', 'mlsl_location_cat_init');

if (!function_exists('mlsl_location_cat_updated_messages')) {
    function mlsl_location_cat_updated_messages($messages)
    {
        $singular = get_option("_wpt_mlsl_settings_locations_cat_label_singular", 'Location Category');
        $plural   = get_option("_wpt_mlsl_settings_locations_cat_label_plural", 'Location Categories');

        $messages['mlsl-location-cat'] = [
            0 => '', // Unused. Messages start at index 1.
            1 => __("{$singular} added.", 'seo-for-local'),
            2 => __("{$singular} deleted.", 'seo-for-local'),
            3 => __("{$singular} updated.", 'seo-for-local'),
            4 => __("{$singular} not added.", 'seo-for-local'),
            5 => __("{$singular} not updated.", 'seo-for-local'),
            6 => __("{$plural} deleted.", 'seo-for-local'),
        ];

        return $messages;
    }
}

add_filter('term_updated_messages', 'mlsl_location_cat_updated_messages');
