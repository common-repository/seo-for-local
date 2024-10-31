<?php

namespace WPT_MLSL_Divi_Modules\LocationAddressModule;

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
            'address'                 => [
            'selector' => "%%order_class%% .mlsl-address",
            'label'    => 'Address',
        ],
            'no_location_found_error' => [
            'selector' => "%%order_class%% .no-location-found",
            'label'    => 'No Location Found Error',
        ],
            'location_part'           => [
            'selector' => "%%order_class%% .location-part",
            'label'    => 'Location Part',
        ],
            'address_separator'       => [
            'selector' => "%%order_class%% .mlsl-address-separator",
            'label'    => 'Address Separator',
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
            'use_current_post'        => 'on',
            'location_post'           => 'loc-0',
            'layout'                  => 'single',
            'show_no_location_error'  => 'on',
            'no_location_found_error' => 'No Location Found',
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
        $fields['admin_label'] = [
            'label'       => __( 'Admin Label', 'et_builder' ),
            'type'        => 'text',
            'description' => 'This will change the label of the module in the builder for easy identification.',
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