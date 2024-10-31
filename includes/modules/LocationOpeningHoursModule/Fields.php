<?php

namespace WPT_MLSL_Divi_Modules\LocationOpeningHoursModule;

/**
 * .
 */
class Fields
{
    protected  $container ;
    protected  $module ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    /**
     * Set the module instance.
     */
    public function set_module( $module )
    {
        $this->module = $module;
    }
    
    /**
     * Get selector
     */
    public function get_selector( $key )
    {
        $selectors = $this->get_selectors();
        return $selectors[$key]['selector'];
    }
    
    /**
     * List of selectors
     */
    public function get_selectors()
    {
        return [
            'opening_hours_container' => [
            'selector' => "%%order_class%% .mlsl-opening-hours",
            'label'    => 'Opening Hours Container',
        ],
            'table'                   => [
            'selector' => "%%order_class%% .mlsl-opening-hours table",
            'label'    => 'Table',
        ],
            'row'                     => [
            'selector' => "%%order_class%% .mlsl-opening-hours table tr",
            'label'    => 'Row',
        ],
            'odd_row'                 => [
            'selector' => "%%order_class%% .mlsl-opening-hours table tr:nth-child(odd)",
            'label'    => 'Odd Row',
        ],
            'even_row'                => [
            'selector' => "%%order_class%% .mlsl-opening-hours table tr:nth-child(even)",
            'label'    => 'Even Row',
        ],
            'data_cell'               => [
            'selector' => "%%order_class%% .mlsl-opening-hours table tr td",
            'label'    => 'Data Cell',
        ],
            'day_label'               => [
            'selector' => "%%order_class%% .mlsl-opening-hours table tr td.mlsl-day-label",
            'label'    => 'Day Data Cell',
        ],
            'timing_cell'             => [
            'selector' => "%%order_class%% .mlsl-opening-hours table tr td.mlsl-day-timings-cell",
            'label'    => 'Timing Data Cell',
        ],
            'time_container'          => [
            'selector' => "%%order_class%% .mlsl-opening-hours table tr td.mlsl-day-timings-cell .mlsl-timings",
            'label'    => 'Timings Container',
        ],
            'time'                    => [
            'selector' => "%%order_class%% .mlsl-opening-hours table tr td.mlsl-day-timings-cell .mlsl-timings .mlsl-timing",
            'label'    => 'Timings',
        ],
            'closed_label'            => [
            'selector' => "%%order_class%% .mlsl-timing-closed",
            'label'    => 'Closed Label',
        ],
            'no_location_found_error' => [
            'selector' => "%%order_class%% .no-location-found",
            'label'    => 'No Location Found Error',
        ],
        ];
    }
    
    /**
     * Get default for given keys
     */
    public function get_default( $key )
    {
        $defaults = $this->get_defaults();
        return ( isset( $defaults[$key] ) ? $defaults[$key] : '' );
    }
    
    /**
     * Get defaults
     */
    public function get_defaults()
    {
        $defaults = [
            'use_current_post'            => 'on',
            'location_post'               => 'loc-0',
            'show_no_location_error'      => 'on',
            'no_location_found_error'     => 'Location Not Found',
            'first_day_of_week'           => 'monday',
            'show_closed_days'            => 'on',
            'closed_label'                => 'Closed',
            'time_format'                 => '12_hour',
            'data_cell_border_style'      => 'solid',
            'data_cell_border_width'      => '1px',
            'data_cell_border_color'      => '#eeeeee',
            'data_cell_custom_margin'     => '0|0|0|0|false|false',
            'data_cell_custom_padding'    => '6px|6px|6px|6px|true|true',
            'closed_label_custom_margin'  => '0|0|0|0|false|false',
            'closed_label_custom_padding' => '0|0|0|0|false|false',
            'odd_row_bg'                  => '',
            'even_row_bg'                 => '',
        ];
        return $defaults;
    }
    
    /**
     * Get module fields
     */
    public function get_fields()
    {
        $fields = [];
        $fields = array_merge( $fields, $this->get_location_fields() );
        $fields = array_merge( $fields, $this->opening_hours_fields() );
        $fields['admin_label'] = [
            'label'       => __( 'Admin Label', 'et_builder' ),
            'type'        => 'text',
            'description' => 'This will change the label of the module in the builder for easy identification.',
        ];
        return $fields;
    }
    
    /**
     * Opening hours fields
     */
    public function opening_hours_fields()
    {
        $fields = [];
        $fields['first_day_of_week'] = [
            'label'       => esc_html__( 'First Day Of Week', 'et_builder' ),
            'type'        => 'select',
            'options'     => [
            'monday' => 'Monday',
            'sunday' => 'Sunday',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'opening_hours',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'first_day_of_week' ),
        ];
        $fields['show_closed_days'] = [
            'label'       => esc_html__( 'Show Closed Days', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'opening_hours',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_closed_days' ),
        ];
        $fields['closed_label'] = [
            'label'       => esc_html__( 'Closed Label', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'opening_hours',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_closed_days' => 'on',
        ],
            'default'     => $this->get_default( 'closed_label' ),
        ];
        $fields['time_format'] = [
            'label'       => esc_html__( 'Time Format', 'et_builder' ),
            'type'        => 'select',
            'options'     => [
            '24_hour' => '24 Hour',
            '12_hour' => '12 Hour',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'opening_hours',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'time_format' ),
        ];
        return $fields;
    }
    
    /**
     * Get location fields.
     */
    public function get_location_fields()
    {
        $fields = [];
        $fields['use_current_post'] = [
            'label'       => esc_html__( 'Use Current Location Post?', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'location',
            'description' => esc_html__( 'Select "ON" to use the current post for location data.', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'use_current_post' ),
        ];
        $fields['location_post'] = [
            'label'       => esc_html__( 'Select Location Post', 'et_builder' ),
            'type'        => 'select',
            'options'     => $this->container['locations_crud']->all_by_id_name(),
            'tab_slug'    => 'general',
            'toggle_slug' => 'location',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'use_current_post' => 'off',
        ],
            'default'     => $this->get_default( 'location_post' ),
        ];
        $fields['show_no_location_error'] = [
            'label'       => esc_html__( 'Show "No Location Found" Error', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'location',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_no_location_error' ),
        ];
        $fields['no_location_found_error'] = [
            'label'       => esc_html__( 'No Location Found Error', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'location',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_no_location_error' => 'on',
        ],
            'default'     => $this->get_default( 'no_location_found_error' ),
        ];
        return $fields;
    }
    
    public function get_css_fields()
    {
        $selectors = [];
        return $selectors;
    }
    
    public function set_advanced_toggles( &$toggles )
    {
        $selectors = $this->get_selectors();
        foreach ( $selectors as $slug => $selector ) {
            $toggles['advanced']['toggles'][$slug] = $selector['label'];
        }
    }
    
    /**
     * Advanced font definition
     */
    public function get_advanced_font_definition( $key )
    {
        return [
            'css' => [
            'main'      => $this->get_selector( $key ),
            'important' => 'all',
        ],
        ];
    }
    
    public function set_advanced_font_definition( &$config, $key )
    {
        $config['fonts'][$key] = $this->get_advanced_font_definition( $key );
    }

}