<?php

namespace WPT_MLSL_Divi_Modules\LocationOpeningHoursModule;

use  ET_Builder_Module ;
class LocationOpeningHoursModule extends ET_Builder_Module
{
    public  $slug = 'et_pb_mlsl_location_opening_hours' ;
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
        'module_uri' => 'https://wptools.app/wordpress-plugin/local-seo-for-divi-gutenberg-blocks/?utm_source=divi-module&utm_medium=page&utm_campaign=local-seo&utm_content=opening-hours#pricing',
        'author'     => 'WP Tools → Get 7 day FREE Trial',
        'author_uri' => 'https://wptools.app/wordpress-plugin/local-seo-for-divi-gutenberg-blocks/?utm_source=divi-module&utm_medium=page&utm_campaign=local-seo&utm_content=opening-hours#pricing',
    ) ;
    /**
     * init divi module *
     */
    public function init()
    {
        $this->name = esc_html__( 'Location Opening Hours', '' );
        $this->icon_path = $this->container['plugin_dir'] . '/images/time.svg';
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
            'location'      => esc_html__( 'Location', 'et_builder' ),
            'opening_hours' => esc_html__( 'Opening Hours', 'et_builder' ),
        ],
        ],
            'advanced' => [
            'toggles' => [
            'table'          => esc_html__( 'Table', 'et_builder' ),
            'row'            => esc_html__( 'Row', 'et_builder' ),
            'odd_row'        => esc_html__( 'Odd Row', 'et_builder' ),
            'even_row'       => esc_html__( 'Even Row', 'et_builder' ),
            'data_cell'      => esc_html__( 'Data Cell', 'et_builder' ),
            'day_label'      => esc_html__( 'Day Label', 'et_builder' ),
            'data_cell_time' => esc_html__( 'Data Cell - Time', 'et_builder' ),
            'time_container' => esc_html__( 'Time Container', 'et_builder' ),
            'time'           => esc_html__( 'Time', 'et_builder' ),
            'closed_label'   => esc_html__( 'Closed Label', 'et_builder' ),
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
        $time_format = $this->container['divi']->get_prop_value( $this, 'time_format' );
        $show_closed_days = $this->container['divi']->get_prop_value( $this, 'show_closed_days' );
        $first_day_of_week = $this->container['divi']->get_prop_value( $this, 'first_day_of_week' );
        $odd_row_bg = $this->container['divi']->get_prop_value( $this, 'odd_row_bg' );
        $even_row_bg = $this->container['divi']->get_prop_value( $this, 'even_row_bg' );
        $closed_label = $this->container['divi']->get_prop_value( $this, 'closed_label' );
        
        if ( $attrs['use_current_post'] == 'on' ) {
            // phpcs:ignore
            
            if ( wp_doing_ajax() && isset( $_POST['options'], $_POST['options']['current_page'], $_POST['options']['current_page']['id'] ) ) {
                // phpcs:ignore
                $post_id = (int) $_POST['options']['current_page']['id'];
            } else {
                // phpcs:ignore
                global  $post ;
                $post_id = $post->ID;
            }
        
        } else {
            $post_id = str_replace( 'loc-', '', $attrs['location_post'] );
        }
        
        
        if ( isset( $post_id ) ) {
            $opening_hours = $this->container['locations']->get_opening_hours( $post_id );
            $opening_hours_map = [];
            foreach ( $opening_hours as $opening_hour ) {
                // phpcs:ignore
                $opening_hour['opens'] = ( $time_format == '24_hour' ? $opening_hour['opens'] : date( 'h:i A', strtotime( $opening_hour['opens'] ) ) );
                // phpcs:ignore
                $opening_hour['closes'] = ( $time_format == '24_hour' ? $opening_hour['closes'] : date( 'h:i A', strtotime( $opening_hour['closes'] ) ) );
                $opening_hours_map[$opening_hour['day']][] = $opening_hour;
            }
            
            if ( $first_day_of_week == 'monday' ) {
                $days = [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday'
                ];
            } else {
                $days = [
                    'Sunday',
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday'
                ];
            }
        
        }
        
        // styles
        \ET_Builder_Element::set_style( $render_slug, [
            'selector'    => $this->helper()->get_selector( 'data_cell' ),
            'declaration' => sprintf(
            'border:%s %s %s; border-color:%s;',
            $attrs['data_cell_border_width'],
            $attrs['data_cell_border_style'],
            $attrs['data_cell_border_color'],
            $attrs['data_cell_border_color']
        ),
        ] );
        \ET_Builder_Element::set_style( $render_slug, [
            'selector'    => $this->helper()->get_selector( 'table' ),
            'declaration' => 'border: none;',
        ] );
        // Data cell margin padding style
        $this->container['divi']->process_advanced_margin_padding_css(
            $this,
            'data_cell',
            $render_slug,
            $this->margin_padding
        );
        // Closed label margin padding style
        $this->container['divi']->process_advanced_margin_padding_css(
            $this,
            'closed_label',
            $render_slug,
            $this->margin_padding
        );
        if ( $odd_row_bg ) {
            \ET_Builder_Element::set_style( $render_slug, [
                'selector'    => $this->helper()->get_selector( 'odd_row' ),
                'declaration' => sprintf( 'background-color:%s;', $odd_row_bg ),
            ] );
        }
        if ( $even_row_bg ) {
            \ET_Builder_Element::set_style( $render_slug, [
                'selector'    => $this->helper()->get_selector( 'even_row' ),
                'declaration' => sprintf( 'background-color:%s;', $even_row_bg ),
            ] );
        }
        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/location-opening-hours.php';
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