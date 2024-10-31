<?php
namespace WPT\MLSL\CustomFields;

/**
 * CarbonFields.
 */
class CarbonFields
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function after_setup_theme()
    {
        if (!defined('Carbon_Fields\DIR')) {
            define('Carbon_Fields\DIR', $this->container['plugin_dir'] . '/vendor/htmlburger/carbon-fields');
        }

        \Carbon_Fields\Carbon_Fields::boot();

        do_action('wpt_mlsl_carbon_fields_after_boot');
    }

}
