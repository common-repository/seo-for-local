<?php

namespace WPT\MLSL\Divi;

/**
 * Divi.
 */
class Divi
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
     * ET builder ready.
     */
    public function et_builder_ready()
    {
        new \WPT_MLSL_Divi_Modules\LocationMapModule\LocationMapModule( $this->container );
        new \WPT_MLSL_Divi_Modules\LocationAddressModule\LocationAddressModule( $this->container );
        new \WPT_MLSL_Divi_Modules\LocationOpeningHoursModule\LocationOpeningHoursModule( $this->container );
    }
    
    /**
     * Divi extension register
     */
    public function divi_extensions_init()
    {
        new \WPT_MLSL_Divi_Modules\MultiLocationsExtension( $this->container );
    }
    
    /**
     * Process custom margin and padding.
     */
    public function process_advanced_margin_padding_css(
        $module,
        $prop_name,
        $function_name,
        $margin_padding
    )
    {
        $utils = \ET_Core_Data_Utils::instance();
        $all_values = $module->props;
        $advanced_fields = $module->advanced_fields;
        // Disable if module doesn't set advanced_fields property and has no VB support.
        if ( !$module->has_vb_support() && !$module->has_advanced_fields ) {
            return;
        }
        $allowed_advanced_fields = [ $prop_name . '_margin_padding' ];
        foreach ( $allowed_advanced_fields as $advanced_field ) {
            if ( !empty($advanced_fields[$advanced_field]) ) {
                foreach ( $advanced_fields[$advanced_field] as $option_name => $form_field ) {
                    $margin_key = "{$option_name}_custom_margin";
                    $padding_key = "{$option_name}_custom_padding";
                    
                    if ( '' !== $utils->array_get( $all_values, $margin_key, '' ) || '' !== $utils->array_get( $all_values, $padding_key, '' ) ) {
                        $settings = $utils->array_get( $form_field, 'margin_padding', [] );
                        $form_field_margin_padding_css = $utils->array_get( $settings, 'css.main', '' );
                        if ( empty($form_field_margin_padding_css) ) {
                            $utils->array_set( $settings, 'css.main', $utils->array_get( $form_field, 'css.main', '' ) );
                        }
                        $margin_padding->update_styles(
                            $module,
                            $option_name,
                            $settings,
                            $function_name,
                            $advanced_field
                        );
                    }
                
                }
            }
        }
    }
    
    public function get_prop_value( $module, $prop_name )
    {
        return ( isset( $module->props[$prop_name] ) && $module->props[$prop_name] ? $module->props[$prop_name] : $module->get_default( $prop_name ) );
    }
    
    /**
     * Html module message for premium functionality.
     */
    public function module_message_for_premium_functionality()
    {
        return '<br/><br/><div class="et-fb-settings-options et-fb-option--warning"><div class="et-fb-option-container"><div class="et-fb-main-settings-option" style="line-height:1.5em;">Styling options in the <strong>"Design Tab"</strong> are available in the premium version.</div></div></div>';
    }

}