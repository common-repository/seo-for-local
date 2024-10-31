<?php
namespace WPT\MLSL\Admin;

/**
 * Settings.
 */
class Settings
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Add premium settings page
     */
    public function add_settings_pages()
    {
        $this->container['local_business_settings']->create_settings_page();
    }

    /**
     * Register local business schema fields.
     */
    public function carbon_fields_register_fields()
    {
        $this->container['local_business_custom_fields']->carbon_fields_register_fields();
    }

    public function google_maps_key()
    {
        return trim(get_option('_wpt_mlsl_settings_google_maps_api_key', ''));
    }

    public function google_maps_geocoding_key()
    {
        return trim(get_option('_wpt_mlsl_settings_google_maps_api_geocoding_key', ''));
    }

    public function get_default_marker()
    {
        return trim(get_option('_wpt_mlsl_settings_custom_marker', ''));
    }

    /**
     * get the value of load schema in
     */
    public function get_load_schema_in()
    {
        return get_option('_wpt_mlsl_settings_load_schema_in', 'head');
    }

    /**
     * Check if location post type is private.
     */
    public function is_location_post_type_private()
    {
        return get_option('_wpt_mlsl_settings_make_locations_private', false);
    }

    /**
     * Get default location.
     */
    public function get_default_location_id()
    {
        $locations = carbon_get_theme_option('wpt_mlsl_settings_default_location');

        if (isset($locations[0], $locations[0]['id'])) {
            return $locations[0]['id'];
        }

        return false;
    }

}
