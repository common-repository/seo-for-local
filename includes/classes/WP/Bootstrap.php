<?php

namespace WPT\MLSL\WP;

/**
 * Bootstrap.
 */
class Bootstrap
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
     * Register activation hook
     */
    public function register_activation_hook()
    {
        flush_rewrite_rules( true );
    }
    
    public function wp_head()
    {
        $isPremium = false;
        $settings = [
            'GmKey'             => trim( get_option( '_wpt_mlsl_settings_google_maps_api_key', '' ) ),
            'pv'                => $this->container['plugin_version'],
            'plugin_url'        => $this->container['plugin_url'] . '/',
            'isVisualBuilder'   => isset( $_GET['et_fb'] ) && $_GET['et_fb'] == '1',
            'settings_page_url' => admin_url( 'options-general.php?page=crb_carbon_fields_container_locations__seo.php' ),
            'is_gm_key_set'     => ( trim( get_option( '_wpt_mlsl_settings_google_maps_api_key', '' ) ) === '' ? false : true ),
            'is_premium'        => $isPremium,
        ];
        
        if ( is_admin() ) {
            $screen = get_current_screen();
            if ( $screen->base == 'post' && $screen->post_type == 'wpt-mlsl-locations' ) {
                $settings['disable_gmap_script'] = true;
            }
        }
        
        echo  sprintf( '<script>var wptMlsl = %s; </script>', wp_json_encode( $settings ) ) ;
    }
    
    public function admin_head()
    {
        // phpcs:ignore
        
        if ( isset( $_GET['et_fb'] ) ) {
            ob_start();
            require $this->container['plugin_dir'] . '/resources/views/partials/admin-css.php';
            // phpcs:ignore
            echo  ob_get_clean() ;
        }
    
    }

}