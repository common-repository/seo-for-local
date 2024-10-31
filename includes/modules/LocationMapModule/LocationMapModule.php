<?php

namespace WPT_MLSL_Divi_Modules\LocationMapModule;

use  ET_Builder_Module ;
class LocationMapModule extends ET_Builder_Module
{
    public  $slug = 'et_pb_mlsl_location_map' ;
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
        'module_uri' => 'https://wptools.app/wordpress-plugin/local-seo-for-divi-gutenberg-blocks/?utm_source=divi-module&utm_medium=page&utm_campaign=local-seo&utm_content=map#pricing',
        'author'     => 'WP Tools → Get 7 day FREE Trial',
        'author_uri' => 'https://wptools.app/wordpress-plugin/local-seo-for-divi-gutenberg-blocks/?utm_source=divi-module&utm_medium=page&utm_campaign=local-seo&utm_content=map#pricing',
    ) ;
    /**
     * init divi module *
     */
    public function init()
    {
        $this->name = esc_html__( 'Location Map', '' );
        $this->icon_path = $this->container['plugin_dir'] . '/images/single-location-map-icon.svg';
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
            'map'          => esc_html__( 'Map', 'et_builder' ),
            'map_controls' => esc_html__( 'Map Controls', 'et_builder' ),
            'info_window'  => esc_html__( 'Info Window', 'et_builder' ),
            'main_content' => esc_html__( 'Main Content', 'et_builder' ),
        ],
        ],
            'advanced' => [
            'toggles' => [
            'map'                     => 'Map',
            'marker'                  => 'Marker',
            'info_window_title'       => 'Info Window Title',
            'info_window_description' => 'Info Window Description',
            'info_window_address'     => 'Info Window Address',
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
        $config['max_width'] = false;
        $config['link_options'] = false;
        $config['transform'] = false;
        
        if ( !wpt_mlsl()->is_premium() ) {
            $config['fonts'] = false;
            $config['border'] = false;
            $config['borders'] = false;
            $config['box_shadow'] = false;
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
        $this->add_classname( [ 'wpt-mlsl-location-map-container' ] );
        $module_class = $this->module_classname( $render_slug );
        $attrs = wp_parse_args( $attrs, $this->helper()->get_defaults() );
        $lat = 0;
        $lng = 0;
        $zoom = 8;
        $gesture_handling = $this->container['divi']->get_prop_value( $this, 'gesture_handling' );
        $map_type = $this->container['divi']->get_prop_value( $this, 'map_type' );
        $show_fullscreen_control = $this->container['divi']->get_prop_value( $this, 'show_fullscreen_control' );
        $fullscreen_control_position = $this->container['divi']->get_prop_value( $this, 'fullscreen_control_position' );
        $show_rotate_control = $this->container['divi']->get_prop_value( $this, 'show_rotate_control' );
        $rotate_control_position = $this->container['divi']->get_prop_value( $this, 'rotate_control_position' );
        $show_street_view_control = $this->container['divi']->get_prop_value( $this, 'show_street_view_control' );
        $street_view_control_position = $this->container['divi']->get_prop_value( $this, 'street_view_control_position' );
        $show_scale_control = $this->container['divi']->get_prop_value( $this, 'show_scale_control' );
        $show_map_type_control = $this->container['divi']->get_prop_value( $this, 'show_map_type_control' );
        $map_control_position = $this->container['divi']->get_prop_value( $this, 'map_control_position' );
        $show_zoom_control = $this->container['divi']->get_prop_value( $this, 'show_zoom_control' );
        $zoom_control_position = $this->container['divi']->get_prop_value( $this, 'zoom_control_position' );
        $marker_icon = $this->container['divi']->get_prop_value( $this, 'marker_icon' );
        $map_control_map_types_field_val = $this->container['divi']->get_prop_value( $this, 'map_control_map_types' );
        $show_address = $this->container['divi']->get_prop_value( $this, 'show_address' );
        $show_direction_link = $this->container['divi']->get_prop_value( $this, 'show_direction_link' );
        $show_description = $this->container['divi']->get_prop_value( $this, 'show_description' );
        $show_title = $this->container['divi']->get_prop_value( $this, 'show_title' );
        $show_info_window = $this->container['divi']->get_prop_value( $this, 'show_info_window' );
        $marker_animation = $this->container['divi']->get_prop_value( $this, 'marker_animation' );
        $map_height = $this->container['divi']->get_prop_value( $this, 'map_height' );
        $map_style = $this->container['divi']->get_prop_value( $this, 'map_style' );
        $direction_image_alt_title = 'Click to open directions in `Google Maps`';
        if ( $map_style ) {
            $map_style = wp_strip_all_tags( trim( $map_style ) );
        }
        $map_control_map_types_chunks = explode( '|', $map_control_map_types_field_val );
        $map_control_map_types = [];
        if ( $map_control_map_types_chunks[0] == 'on' ) {
            $map_control_map_types[] = 'hybrid';
        }
        if ( $map_control_map_types_chunks[1] == 'on' ) {
            $map_control_map_types[] = 'roadmap';
        }
        if ( $map_control_map_types_chunks[2] == 'on' ) {
            $map_control_map_types[] = 'satellite';
        }
        if ( $map_control_map_types_chunks[3] == 'on' ) {
            $map_control_map_types[] = 'terrain';
        }
        $meta_keys = [
            '_mlsl_map|||0|lat',
            '_mlsl_map|||0|lng',
            '_mlsl_map|||0|zoom',
            '_mlsl_street_address',
            '_mlsl_locality',
            '_mlsl_postal_code',
            '_mlsl_region',
            '_mlsl_country'
        ];
        $location_title = '';
        
        if ( $attrs['use_current_post'] == 'on' ) {
            // phpcs:ignore
            
            if ( wp_doing_ajax() && isset( $_POST['options'], $_POST['options']['current_page'], $_POST['options']['current_page']['id'] ) ) {
                // phpcs:ignore
                $post_id = (int) $_POST['options']['current_page']['id'];
                
                if ( false === get_post_status( $post_id ) ) {
                } else {
                    $location_post = get_post( $post_id );
                    $location_title = $location_post->post_title;
                }
            
            } else {
                // phpcs:ignore
                global  $post ;
                $location_post = $post;
                $location_title = $location_post->post_title;
            }
            
            if ( isset( $location_post ) ) {
                $meta_data = $this->container['locations_crud']->get_post_meta( $location_post->ID, $meta_keys );
            }
        } else {
            $post_id = str_replace( 'loc-', '', $attrs['location_post'] );
            
            if ( $post_id ) {
                $meta_data = $this->container['locations_crud']->get_post_meta( $post_id, $meta_keys );
                $location_post = get_post( $post_id );
                if ( $location_post ) {
                    $location_title = $location_post->post_title;
                }
            }
        
        }
        
        $address = [];
        $address_string = '';
        $directions_link = '';
        
        if ( isset( $meta_data ) ) {
            $lat = ( $meta_data['_mlsl_map|||0|lat'] ? esc_attr( et_()->to_css_decimal( $meta_data['_mlsl_map|||0|lat'] ) ) : 0 );
            $lng = ( $meta_data['_mlsl_map|||0|lng'] ? esc_attr( et_()->to_css_decimal( $meta_data['_mlsl_map|||0|lng'] ) ) : 0 );
            $zoom = ( $meta_data['_mlsl_map|||0|zoom'] ? esc_attr( et_()->to_css_decimal( $meta_data['_mlsl_map|||0|zoom'] ) ) : 8 );
            $directions_link = sprintf( 'https://www.google.com/maps/dir/?api=1&destination=%s,%s', $lat, $lng );
            $address = [];
            if ( $meta_data['_mlsl_street_address'] ) {
                $address[] = $meta_data['_mlsl_street_address'];
            }
            if ( $meta_data['_mlsl_locality'] ) {
                $address[] = $meta_data['_mlsl_locality'];
            }
            if ( $meta_data['_mlsl_postal_code'] ) {
                $address[] = $meta_data['_mlsl_postal_code'];
            }
            if ( $meta_data['_mlsl_region'] ) {
                $address[] = $meta_data['_mlsl_region'];
            }
            if ( $meta_data['_mlsl_country'] ) {
                $address[] = $meta_data['_mlsl_country'];
            }
            $address_string = implode( ', ', $address );
        }
        
        $location_description = '';
        
        if ( isset( $location_post ) ) {
            $location_description = $location_post->post_excerpt;
        } else {
            $location_post = false;
        }
        
        $this->container['google_maps']->dequeue_scripts();
        wp_enqueue_script( 'wpt-mlsl-locations-frontend-bundle' );
        // styles
        \ET_Builder_Element::set_style( $render_slug, [
            'selector'    => $this->helper()->get_selector( 'map' ),
            'declaration' => sprintf( 'height:%s;', $attrs['map_height'] ),
        ] );
        $info_window_html = '';
        
        if ( $show_info_window == 'on' ) {
            ob_start();
            require $this->container['plugin_dir'] . '/resources/views/partials/location-map-info-window.php';
            $info_window_html = ob_get_clean();
            $info_window_html = apply_filters(
                'mlsl_location_map_info_window_html',
                $info_window_html,
                $this->props,
                $location_title,
                $location_description,
                $address_string,
                $location_post
            );
        }
        
        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/location-map.php';
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