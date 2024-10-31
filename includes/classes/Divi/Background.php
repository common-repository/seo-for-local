<?php
namespace WPT\MLSL\Divi;

/**
 * Background.
 */
class Background
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function setup_field(
         $option_key,
         $option_name,
         $default_value = '',
        &$fields,
         $module,
         $description = '',
         $tab_slug = 'general',
         $toggle_slug = 'background',
         $show_if = []
    ) {
        $field_key = sprintf('%s_color', $option_key);

        $fields[$field_key] = [
            'label'             => esc_html__($option_name, 'seo-for-local'),
            'type'              => 'background-field',
            'base_name'         => $option_key,
            'context'           => $field_key,
            'option_category'   => 'button',
            'custom_color'      => true,
            'default'           => $default_value,
            'background_fields' => $module->generate_background_options($option_key, 'button', $tab_slug, $toggle_slug, $field_key),
            'hover'             => 'tabs',
            'tab_slug'          => $tab_slug,
            'toggle_slug'       => $toggle_slug,
            'show_if'           => $show_if,
            'description'       => esc_html__($description, 'seo-for-local'),
        ];

        $fields += $module->generate_background_options($option_key, 'skip', 'general', 'background', $field_key);
    }

    /**
     * Process background options
     */
    public function process_background($args)
    {
        return \ET_Builder_Module_Helper_Background::instance()
            ->get_background_style($args);
    }

    /**
     * Appends background class name to the module classname
     */
    public function add_classname(
        $module,
        $props
    ) {
        $background_layout_class_names = et_pb_background_layout_options()->get_background_layout_class($props);
        $this->module->add_classname(
            [
                $background_layout_class_names[0],
            ]
        );
    }

}
