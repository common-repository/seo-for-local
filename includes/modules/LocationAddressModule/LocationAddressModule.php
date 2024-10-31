<?php

namespace WPT_MLSL_Divi_Modules\LocationAddressModule;

use  ET_Builder_Module ;
class LocationAddressModule extends ET_Builder_Module
{
    public  $slug = 'et_pb_mlsl_location_address' ;
    public  $vb_support = 'on' ;
    protected  $container ;
    protected  $helper ;
    public  $icon_path ;
    public function __construct( $container )
    {
        $this->container = $container;
        parent::__construct();
    }
    
    protected  $module_credits = array(
        'module_uri' => 'https://wptools.app/wordpress-plugin/local-seo-for-divi-gutenberg-blocks/?utm_source=divi-module&utm_medium=page&utm_campaign=local-seo&utm_content=address#pricing',
        'author'     => 'WP Tools → Get 7 day FREE Trial',
        'author_uri' => 'https://wptools.app/wordpress-plugin/local-seo-for-divi-gutenberg-blocks/?utm_source=divi-module&utm_medium=page&utm_campaign=local-seo&utm_content=address#pricing',
    ) ;
    /**
     * init divi module *
     */
    public function init()
    {
        $this->name = esc_html__( 'Location Address', '' );
        $this->icon_path = $this->container['plugin_dir'] . '/images/address.svg';
        $this->module_credits['author'] .= $this->container['divi']->module_message_for_premium_functionality();
    }
    
    /**
     * get the fields helper class *
     */
    public function helper()
    {
        
        if ( !$this->helper ) {
            $this->helper = new Fields( $this->container );
            $this->helper->set_module( $this );
        }
        
        return $this->helper;
    }
    
    /**
     * get the module toggles *
     */
    public function get_settings_modal_toggles()
    {
        return [
            'general'  => [
            'toggles' => [
            'location' => esc_html__( 'Location', 'et_builder' ),
        ],
        ],
            'advanced' => [
            'toggles' => [
            'address'                 => esc_html__( 'Address', 'et_builder' ),
            'no_location_found_error' => esc_html__( '"No Location Found" Error', 'et_builder' ),
        ],
        ],
        ];
    }
    
    /**
     * get the css fields for advanced divi module settings *
     */
    public function get_custom_css_fields_config()
    {
        return $this->helper()->get_css_fields();
    }
    
    /**
     * get the advanced field for divi module settings *
     */
    public function get_advanced_fields_config()
    {
        $config['text'] = false;
        $config['filters'] = false;
        $config['animation'] = false;
        $config['text_shadow'] = false;
        $config['fonts'] = [];
        $config['link_options'] = false;
        $config['transform'] = false;
        
        if ( !wpt_mlsl()->is_premium() ) {
            $config['border'] = false;
            $config['borders'] = false;
            $config['box_shadow'] = false;
            $config['max_width'] = false;
            $config['margin_padding'] = false;
        }
        
        return $config;
    }
    
    /**
     * get the divi module fields *
     */
    public function get_fields()
    {
        return $this->helper()->get_fields();
    }
    
    /**
     * Render the divi module *
     */
    public function render( $attrs, $content = null, $render_slug = null )
    {
        $attrs = wp_parse_args( $attrs, $this->helper()->get_defaults() );
        $show_no_location_error = $this->container['divi']->get_prop_value( $this, 'show_no_location_error' );
        $no_location_found_error = $this->container['divi']->get_prop_value( $this, 'no_location_found_error' );
        
        if ( $attrs['use_current_post'] == 'on' ) {
            // phpcs:ignore
            
            if ( wp_doing_ajax() && isset( $_POST['options'], $_POST['options']['current_page'], $_POST['options']['current_page']['id'] ) ) {
                // phpcs:ignore
                $post_id = (int) $_POST['options']['current_page']['id'];
                $location = get_post( $post_id );
            } else {
                // phpcs:ignore
                global  $post ;
                $location = $post;
            }
        
        } else {
            $post_id = str_replace( 'loc-', '', $attrs['location_post'] );
            $location = get_post( $post_id );
        }
        
        
        if ( isset( $location ) && $location ) {
            $location_data = $this->container['locations']->get( $location );
            $address_array = $this->container['locations']->get_address_array( $location_data );
            if ( !empty($address_array) ) {
                $address_array = array_map( function ( $item ) {
                    return sprintf( '<span class="location-part">%s</span>', $item );
                }, $address_array );
            }
            $separator = ( $attrs['layout'] == 'single' ? '<span class="mlsl-address-separator">, </span>' : '<br class="mlsl-address-separator"/>' );
        }
        
        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/location-address.php';
        return ob_get_clean();
    }
    
    /**
     * Get the default value for the field *
     */
    public function get_default( $key )
    {
        return $this->helper()->get_default( $key );
    }
    
    /**
     * Get the css selector *
     */
    public function get_selector( $key )
    {
        return $this->helper()->get_selector( $key );
    }

}