<?php

namespace WPT_MLSL_Divi_Modules\LocationMapModule;

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
            'map'                               => [
            'selector' => "%%order_class%% .wpt-mlsl-location-map",
            'label'    => 'Map',
        ],
            'info_window_container'             => [
            'selector' => "%%order_class%% .wpt-mlsl-location-map-container .wpt-map-info-window-container",
            'label'    => 'Info Window Container',
        ],
            'info_window_title'                 => [
            'selector' => "%%order_class%% .wpt-location-title",
            'label'    => 'Info Window Title',
        ],
            'info_window_description'           => [
            'selector' => "%%order_class%% .wpt-location-description",
            'label'    => 'Info Window Description',
        ],
            'info_window_address'               => [
            'selector' => "%%order_class%% .wpt-location-address",
            'label'    => 'Info Window Address',
        ],
            'title_address_direction_container' => [
            'selector' => "%%order_class%% .wpt-mlsl-location-map-container .title-address-direction-container",
            'label'    => 'Title, Address & Direction Container',
        ],
            'title_address_container'           => [
            'selector' => "%%order_class%% .wpt-mlsl-location-map-container .title-address-container",
            'label'    => 'Title Address Container',
        ],
            'direction_container'               => [
            'selector' => "%%order_class%% .wpt-map-info-window-container .direction-container",
            'label'    => 'Direction Container',
        ],
            'direction_image'                   => [
            'selector' => "%%order_class%% .wpt-map-info-window-container .direction-container img",
            'label'    => 'Direction Image',
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
        $marker_icon = get_option( '_wpt_mlsl_settings_custom_marker', '' );
        $defaults = [
            'use_current_post'             => 'on',
            'location_post'                => 'loc-0',
            'mouse_wheel'                  => 'on',
            'gesture_handling'             => 'auto',
            'map_type'                     => 'roadmap',
            'show_fullscreen_control'      => 'on',
            'show_rotate_control'          => 'on',
            'show_street_view_control'     => 'on',
            'street_view_control_position' => 'RIGHT_BOTTOM',
            'rotate_control_position'      => 'TOP_LEFT',
            'show_scale_control'           => 'on',
            'show_map_type_control'        => 'on',
            'map_control_map_types'        => 'off|on|off|off',
            'map_control_position'         => 'TOP_LEFT',
            'show_zoom_control'            => 'on',
            'zoom_control_position'        => 'RIGHT_BOTTOM',
            'fullscreen_control_position'  => 'TOP_RIGHT',
            'marker_icon'                  => $marker_icon,
            'show_address'                 => 'on',
            'show_direction_link'          => 'on',
            'show_description'             => 'on',
            'show_title'                   => 'on',
            'show_info_window'             => 'on',
            'marker_animation'             => 'DROP',
            'map_style'                    => '',
            'map_height'                   => '400px',
        ];
        return $defaults;
    }
    
    /**
     * Get module fields
     */
    public function get_fields()
    {
        $fields = [];
        $fields['google_maps_script_notice'] = [
            'type'        => 'warning',
            'value'       => ( trim( get_option( '_wpt_mlsl_settings_google_maps_api_key', '' ) ) === '' ? false : true ),
            'display_if'  => false,
            'message'     => esc_html__( sprintf( 'The Google Maps API Key is currently not set in the <a href="%s" target="_blank">Location & SEO</a> settings. This module will not function properly without the Google Maps API.', admin_url( 'options-general.php?page=crb_carbon_fields_container_locations__seo.php' ) ), 'et_builder' ),
            'toggle_slug' => 'map',
        ];
        $fields['use_current_post'] = [
            'label'       => esc_html__( 'Use Current Location Post?', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map',
            'description' => esc_html__( 'Select "ON" to use the current post for location data.', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'use_current_post' ),
        ];
        $fields['location_post'] = [
            'label'       => esc_html__( 'Select Location Post', 'et_builder' ),
            'type'        => 'select',
            'options'     => $this->container['locations_crud']->all_by_id_name(),
            'tab_slug'    => 'general',
            'toggle_slug' => 'map',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'use_current_post' => 'off',
        ],
            'default'     => $this->get_default( 'location_post' ),
        ];
        $fields['map_type'] = [
            'label'       => esc_html__( 'Map Type', 'et_builder' ),
            'type'        => 'select',
            'options'     => [
            "hybrid"    => "Hybrid",
            "roadmap"   => "Roadmap",
            "satellite" => "Satellite",
            "terrain"   => "Terrain",
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map',
            'description' => esc_html__( 'Select a map type. <br/><br/><b>Hybrid<b> ➔    This map type displays a transparent layer of major streets on satellite images.
<br/><br/><b>Roadmap<b> ➔   This map type displays a normal street map.
<br/><br/><b>Satellite<b> ➔ This map type displays satellite images.
<br/><br/><b>Terrain<b> ➔   This map type displays maps with physical features such as terrain and vegetation.', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'map_type' ),
        ];
        $fields['gesture_handling'] = [
            'label'       => esc_html__( 'Gesture Handling', 'et_builder' ),
            'type'        => 'select',
            'options'     => [
            'auto'        => 'Auto',
            'cooperative' => 'Co-operative',
            'greedy'      => 'Greedy',
            'none'        => 'None',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map',
            'description' => esc_html__( 'Controls how the API handles gestures on the map.<br/><br/><b>Auto</b> ➔ Gesture handling is either cooperative or greedy, depending on whether the page is scrollable or in an iframe.<br/><br/> <b>Co-operative</b> ➔ Scroll events and one-finger touch gestures scroll the page, and do not zoom or pan the map. Two-finger touch gestures pan and zoom the map. Scroll events with a ctrl key or ⌘ key pressed zoom the map. In this mode the map cooperates with the page.<br/><br/> <b>Greedy</b> ➔ All touch gestures and scroll events pan or zoom the map.<br/><br/> <b>None</b> ➔ The map cannot be panned or zoomed by user gestures.', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'gesture_handling' ),
        ];
        $fields['marker_icon'] = [
            'label'       => esc_html__( 'Marker Icon', 'et_builder' ),
            'type'        => 'upload',
            'tab_slug'    => 'general',
            'toggle_slug' => 'map',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'marker_icon' ),
        ];
        $fields += $this->get_map_controls_fields();
        $fields += $this->get_info_window_fields();
        $fields['admin_label'] = [
            'label'       => __( 'Admin Label', 'et_builder' ),
            'type'        => 'text',
            'description' => 'This will change the label of the module in the builder for easy identification.',
        ];
        return $fields;
    }
    
    public function get_info_window_fields()
    {
        $fields = [];
        $fields['show_info_window'] = [
            'label'       => esc_html__( 'Show Info Window', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'info_window',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_info_window' ),
        ];
        $fields['show_title'] = [
            'label'       => esc_html__( 'Show Title', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'info_window',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_info_window' => 'on',
        ],
            'default'     => $this->get_default( 'show_title' ),
        ];
        $fields['show_address'] = [
            'label'       => esc_html__( 'Show Address', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'info_window',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_info_window' => 'on',
        ],
            'default'     => $this->get_default( 'show_address' ),
        ];
        $fields['show_description'] = [
            'label'       => esc_html__( 'Show Description', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'info_window',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_info_window' => 'on',
        ],
            'default'     => $this->get_default( 'show_description' ),
        ];
        $fields['show_direction_link'] = [
            'label'       => esc_html__( 'Show Direction Link', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'info_window',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_info_window' => 'on',
        ],
            'default'     => $this->get_default( 'show_direction_link' ),
        ];
        return $fields;
    }
    
    public function get_map_controls_fields()
    {
        $control_positions = [
            'BOTTOM_CENTER' => 'Bottom Center',
            'BOTTOM_LEFT'   => 'Bottom Left',
            'BOTTOM_RIGHT'  => 'Bottom Right',
            'LEFT_BOTTOM'   => 'Left Bottom',
            'LEFT_CENTER'   => 'Left Center',
            'LEFT_TOP'      => 'Left Top',
            'RIGHT_BOTTOM'  => 'Right Bottom',
            'RIGHT_CENTER'  => 'Right Center',
            'RIGHT_TOP'     => 'Right Top',
            'TOP_CENTER'    => 'Top Center',
            'TOP_LEFT'      => 'Top Left',
            'TOP_RIGHT'     => 'Top Right',
        ];
        $fields = [];
        $fields['show_map_type_control'] = [
            'label'       => esc_html__( 'Show Map Type Control', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_map_type_control' ),
        ];
        $fields['map_control_map_types'] = [
            'label'       => esc_html__( 'Map Types', 'et_builder' ),
            'type'        => 'multiple_checkboxes',
            'options'     => [
            "hybrid"    => "Hybrid",
            "roadmap"   => "Roadmap",
            "satellite" => "Satellite",
            "terrain"   => "Terrain",
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_map_type_control' => 'on',
        ],
            'default'     => $this->get_default( 'map_control_map_types' ),
        ];
        $fields['map_control_position'] = [
            'label'       => esc_html__( 'Map Control Position', 'et_builder' ),
            'type'        => 'select',
            'options'     => $control_positions,
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_map_type_control' => 'on',
        ],
            'default'     => $this->get_default( 'map_control_position' ),
        ];
        $fields['show_zoom_control'] = [
            'label'       => esc_html__( 'Show Zoom Control', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_zoom_control' ),
        ];
        $fields['zoom_control_position'] = [
            'label'       => esc_html__( 'Zoom Control Position', 'et_builder' ),
            'type'        => 'select',
            'options'     => $control_positions,
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_zoom_control' => 'on',
        ],
            'default'     => $this->get_default( 'zoom_control_position' ),
        ];
        $fields['show_scale_control'] = [
            'label'       => esc_html__( 'Show Scale Control', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_scale_control' ),
        ];
        $fields['show_street_view_control'] = [
            'label'       => esc_html__( 'Show Street View Control', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_street_view_control' ),
        ];
        $fields['street_view_control_position'] = [
            'label'       => esc_html__( 'Street View Control Position', 'et_builder' ),
            'type'        => 'select',
            'options'     => $control_positions,
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_street_view_control' => 'on',
        ],
            'default'     => $this->get_default( 'street_view_control_position' ),
        ];
        $fields['show_rotate_control'] = [
            'label'       => esc_html__( 'Show Rotate Control', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_rotate_control' ),
        ];
        $fields['rotate_control_position'] = [
            'label'       => esc_html__( 'Rotate Control Position', 'et_builder' ),
            'type'        => 'select',
            'options'     => $control_positions,
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_rotate_control' => 'on',
        ],
            'default'     => $this->get_default( 'rotate_control_position' ),
        ];
        $fields['show_fullscreen_control'] = [
            'label'       => esc_html__( 'Show Fullscreen Control', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'show_fullscreen_control' ),
        ];
        $fields['fullscreen_control_position'] = [
            'label'       => esc_html__( 'Fullscreen Control Position', 'et_builder' ),
            'type'        => 'select',
            'options'     => $control_positions,
            'tab_slug'    => 'general',
            'toggle_slug' => 'map_controls',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'show_fullscreen_control' => 'on',
        ],
            'default'     => $this->get_default( 'fullscreen_control_position' ),
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