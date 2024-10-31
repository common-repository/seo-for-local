<?php
namespace WPT\MLSL\CustomFields;

use Carbon_Fields\Field;
use Carbon_Fields\Container;

/**
 * LocalBusiness.
 */
class LocalBusiness
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
     * Register local business schema fields.
     */
    public function carbon_fields_register_fields()
    {
        $container = Container::make('post_meta', 'Location Information')
            ->where('post_type', '=', 'wpt-mlsl-locations');

        $fields = [];

        // openingHoursSpecification
        $openingHoursDefaults = carbon_get_theme_option('wpt_mlsl_settings_default_opening_hours');
        $businessType         = carbon_get_theme_option('wpt_mlsl_settings_business_type');
        if (!$businessType) {
            $businessType = 'LocalBusiness';
        }

        if (!$openingHoursDefaults) {
            $openingHoursDefaults = [];
        }

        $openingHoursCustomField = $this->container['opening_hours_custom_field'];

        $openingHoursSpecificationField = Field::make('complex', 'mlsl_opening_hours', 'Opening Hours')
            ->set_collapsed()
            ->set_default_value($openingHoursDefaults)
            ->help_text('Hours during which the business location is open.')
            ->add_fields($openingHoursCustomField->get_fields());
        $openingHoursCustomField->set_heading($openingHoursSpecificationField);

        $fields[] = Field::make('select', 'mlsl_business_type', 'Business Type')
            ->set_options($this->container['business_types']->all())
            ->set_default_value($businessType)
            ->set_help_text('Select the type your business represents.');

        $fields[] = Field::make('text', 'mlsl_tel', 'Telephone')
            ->set_help_text('A business phone number meant to be the primary contact method for customers. Be sure to include the country code and area code in the phone number.');

        $fields[] = Field::make('text', 'mlsl_email', 'Email')
            ->set_help_text('A business email address meant to be the primary contact method for customers.');

        $fields[] = Field::make('text', 'mlsl_fax', 'Fax Number')
            ->set_help_text('The FAX number');

        $fields[] = Field::make('text', 'mlsl_vat', 'VAT ID')
            ->set_help_text('The Value-added Tax ID of the organization');

        $fields[] = Field::make('text', 'mlsl_taxid', 'TAX ID')
            ->set_help_text('The Tax / Fiscal ID of the organization.');

        $fields[] = Field::make('text', 'mlsl_street_address', 'Street Address')
            ->set_help_text('The street address. For example, 1600 Amphitheatre Pkwy.');

        $fields[] = Field::make('text', 'mlsl_locality', 'Locality')
            ->set_help_text('The locality in which the street address is, and which is in the region. For example, Mountain View.');

        $fields[] = Field::make('text', 'mlsl_postal_code', 'Postal Code')
            ->set_help_text('The postal code. For example, 94043');

        $fields[] = Field::make('text', 'mlsl_region', 'Region')
            ->set_help_text('The region in which the locality is, and which is in the country. For example, California');

        $fields[] = Field::make('select', 'mlsl_country', 'Country')
            ->set_options($this->container['local_business_settings']->get_counties_options())
            ->set_help_text('The country. For example, United States');

        $fields[] = Field::make('text', 'mlsl_price', 'Price Range')
            ->set_help_text('The relative price range of a business, commonly specified by either a numerical range (for example, "$10-15") or a normalized number of currency signs (for example, "$$$")');

        $fields[] = Field::make('image', 'mlsl_marker', 'Custom Marker')
            ->set_help_text('Custom Marker For Google Maps. Not used for schema however its used for overriding default marker for store locator');

        $fields[] = $openingHoursSpecificationField;

        $fields[] = Field::make('map', 'mlsl_map', 'Map - Geo locate your business')
            ->set_help_text('Locate & set your business on Google Maps. It captures the latitude and longitude of the location.');

        $fields = apply_filters('mlsl_custom_fields', $fields, $container);

        $container->add_fields($fields);
    }

}
