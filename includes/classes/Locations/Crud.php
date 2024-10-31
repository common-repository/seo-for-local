<?php

namespace WPT\MLSL\Locations;

/**
 * Crud.
 */
class Crud
{
    protected  $container ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    /**
     * Get post meta for location
     */
    public function get_post_meta( $post_id, $meta_keys = array() )
    {
        $meta_keys_formatted = implode( "','", $meta_keys );
        global  $wpdb ;
        $sql = sprintf(
            "select * from %s where post_id=%d and meta_key IN ('%s')",
            $wpdb->postmeta,
            $post_id,
            $meta_keys_formatted
        );
        // phpcs:ignore
        $results = $wpdb->get_results( $sql, ARRAY_A );
        $meta_data = [];
        if ( $results ) {
            foreach ( $results as $result ) {
                // phpcs:ignore
                $meta_data[$result['meta_key']] = $result['meta_value'];
            }
        }
        foreach ( $meta_keys as $meta_key ) {
            if ( !isset( $meta_data[$meta_key] ) ) {
                $meta_data[$meta_key] = '';
            }
        }
        return $meta_data;
    }
    
    /**
     * Get all locations by id and name.
     * For select
     */
    public function all_by_id_name()
    {
        global  $wpdb ;
        $sql = "select ID, post_title from {$wpdb->posts} where post_type='wpt-mlsl-locations' and post_status='publish'";
        // phpcs:ignore
        $results = $wpdb->get_results( $sql, ARRAY_A );
        $options = [
            'loc-0' => '-- Select Location --',
        ];
        if ( $results ) {
            foreach ( $results as $result ) {
                $options['loc-' . $result['ID']] = $result['post_title'];
            }
        }
        return $options;
    }
    
    /**
     * Get all locations.
     */
    public function all( $args )
    {
        $args['post_type'] = 'wpt-mlsl-locations';
        $args['post_status'] = 'publish';
        $locations_query = new \WP_Query( $args );
        $locations = $locations_query->get_posts();
        return $locations;
    }
    
    /**
     * find location by title.
     */
    public function findByTitle( $title )
    {
        $locations = get_posts( [
            'post_type'   => 'wpt-mlsl-locations',
            'post_status' => 'publish',
            'title'       => $title,
        ] );
        
        if ( !empty($locations) ) {
            return $locations[0];
        } else {
            return false;
        }
    
    }
    
    public function setPostMeta(
        $post_id,
        $array_key,
        $array,
        $post_meta_key
    )
    {
        if ( isset( $array[$array_key] ) and $array[$array_key] ) {
            add_post_meta(
                $post_id,
                $post_meta_key,
                $array[$array_key],
                true
            );
        }
    }
    
    /**
     * Clear the count cache
     */
    public function refreshCountCache( $post_id, $post )
    {
        wp_cache_delete( 'wpt_mlsl_locations_count', 'wpt_mlsl' );
    }

}