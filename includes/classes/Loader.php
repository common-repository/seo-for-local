<?php

namespace WPT\MLSL;

use  WPTools\Pimple\Container ;
/**
 * Container
 */
class Loader extends Container
{
    /**
     *
     * @var mixed
     */
    public static  $instance ;
    public function __construct()
    {
        parent::__construct();
        $this['bootstrap'] = function ( $container ) {
            return new WP\Bootstrap( $container );
        };
        $this['divi'] = function ( $container ) {
            return new Divi\Divi( $container );
        };
        $this['divi_background'] = function ( $container ) {
            return new Divi\Background( $container );
        };
        $this['admin_settings'] = function ( $container ) {
            return new Admin\Settings( $container );
        };
        $this['carbon_fields'] = function ( $container ) {
            return new CustomFields\CarbonFields( $container );
        };
        $this['local_business_settings'] = function ( $container ) {
            return new Admin\LocalBusinessSettings( $container );
        };
        $this['local_business_custom_fields'] = function ( $container ) {
            return new CustomFields\LocalBusiness( $container );
        };
        $this['opening_hours_custom_field'] = function ( $container ) {
            return new CustomFields\OpeningHours( $container );
        };
        $this['locations_crud'] = function ( $container ) {
            return new Locations\Crud( $container );
        };
        $this['google_maps'] = function ( $container ) {
            return new WP\GoogleMap( $container );
        };
        $this['notify'] = function ( $container ) {
            return new WP\Notify( $container );
        };
        $this['location_category'] = function ( $container ) {
            return new Locations\Category( $container );
        };
        $this['locations'] = function ( $container ) {
            return new Locations\Locations( $container );
        };
        $this['api'] = function ( $container ) {
            return new Locations\Api( $container );
        };
        $this['business_types'] = function ( $container ) {
            return new Locations\BusinessTypes( $container );
        };
        $this['schema'] = function ( $container ) {
            return new WP\Schema( $container );
        };
        $this['location_map_block'] = function ( $container ) {
            return new Blocks\LocationMap\Block( $container );
        };
        $this['location_opening_hours_block'] = function ( $container ) {
            return new Blocks\LocationOpeningHours\Block( $container );
        };
        $this['location_address_block'] = function ( $container ) {
            return new Blocks\LocationAddress\Block( $container );
        };
        $this['single_location_page_01'] = function ( $container ) {
            return new Blocks\Patterns\SingleLocationPage01( $container );
        };
    }
    
    /**
     * Get container instance.
     */
    public static function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new Loader();
        }
        return self::$instance;
    }
    
    /**
     * Plugin run
     */
    public function run()
    {
        add_action( 'et_builder_ready', [ $this['divi'], 'et_builder_ready' ], 1 );
        add_action( 'divi_extensions_init', [ $this['divi'], 'divi_extensions_init' ] );
        register_activation_hook( $this['plugin_file'], [ $this['bootstrap'], 'register_activation_hook' ] );
        add_action( 'after_setup_theme', [ $this['carbon_fields'], 'after_setup_theme' ] );
        add_action( 'wpt_mlsl_carbon_fields_after_boot', [ $this['admin_settings'], 'add_settings_pages' ] );
        add_action( 'carbon_fields_register_fields', [ $this['admin_settings'], 'carbon_fields_register_fields' ] );
        // load post type if multiple locations are enabled.
        $this['local_business_settings']->check_and_load_post_type();
        add_filter(
            'carbon_fields_map_field_api_key',
            [ $this['local_business_settings'], 'gmaps_api_key' ],
            99,
            1
        );
        add_action( 'wp_print_scripts', [ $this['google_maps'], 'wp_enqueue_scripts' ], 999 );
        add_action(
            'wp_head',
            [ $this['bootstrap'], 'wp_head' ],
            1,
            0
        );
        add_action(
            'admin_head',
            [ $this['bootstrap'], 'wp_head' ],
            1,
            0
        );
        add_action(
            'wp_enqueue_scripts',
            function () {
            // phpcs:ignore
            if ( !isset( $_GET['et_fb'] ) ) {
                wp_dequeue_script( 'wpt-mlsl-locations-frontend-bundle' );
            }
        },
            999,
            0
        );
        
        if ( $this['admin_settings']->get_load_schema_in() == 'head' ) {
            add_action( 'wp_head', [ $this['schema'], 'load_schema' ] );
        } else {
            add_action( 'wp_footer', [ $this['schema'], 'load_schema' ] );
        }
        
        add_action( 'wp_head', [ $this['bootstrap'], 'admin_head' ] );
        $container = $this;
        add_action( 'rest_api_init', function () use( $container ) {
            register_rest_route( 'wpt-store-locator', 'v1/locations-for-block', [
                'methods'             => 'GET',
                'callback'            => [ $container['api'], 'locations_for_block' ],
                'permission_callback' => function () {
                return true;
            },
            ] );
        } );
        add_action(
            'publish_wpt-mlsl-locations',
            [ $this['locations_crud'], 'refreshCountCache' ],
            10,
            2
        );
        add_action(
            'trash_wpt-mlsl-locations',
            [ $this['locations_crud'], 'refreshCountCache' ],
            10,
            2
        );
        add_action(
            'delete_wpt-mlsl-locations',
            [ $this['locations_crud'], 'refreshCountCache' ],
            10,
            2
        );
        add_action( 'init', [ $this['location_map_block'], 'init' ] );
        add_action( 'init', [ $this['location_opening_hours_block'], 'init' ] );
        add_action( 'init', [ $this['location_address_block'], 'init' ] );
        add_action(
            'init',
            function () {
            register_block_pattern_category( 'mlsl', [
                'label' => __( 'Design - Multi Locations (Local SEO)', 'local-seo' ),
            ] );
        },
            10,
            0
        );
        add_action(
            'init',
            [ $this['single_location_page_01'], 'register_block_pattern' ],
            10,
            0
        );
    }

}