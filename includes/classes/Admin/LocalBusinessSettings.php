<?php
namespace WPT\MLSL\Admin;

use Carbon_Fields\Field;
use Carbon_Fields\Container;

/**
 * LocalBusinessSettings.
 */
class LocalBusinessSettings
{
    protected $container;

    protected $setupDone;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->setupDone = false;
    }

    /**
     * Google maps api key
     */
    public function gmaps_api_key($current_key)
    {
        return carbon_get_theme_option('wpt_mlsl_settings_google_maps_api_key');
    }

    /**
     * Create settings page for local business schema.
     */
    public function create_settings_page()
    {
        $field_prefix = 'wpt_mlsl_settings';

        $openingHoursCustomField = $this->container['opening_hours_custom_field'];

        $fields = [];

        $fields[] = Field::make('separator', $field_prefix . '_defaults_separator', 'Defaults');

        $fields[] = Field::make('select', $field_prefix . '_load_schema_in', 'Load Schema In?')
            ->set_options([
                'head'   => 'Header',
                'footer' => 'Footer',
            ])
            ->set_default_value('head')
            ->set_help_text('Select "Header"/"Footer" to load the schema the HTML page header/footer respectively.');

        $fields[] = Field::make('select', $field_prefix . '_business_type', 'Business Type')
            ->set_options($this->container['business_types']->all())
            ->set_default_value('LocalBusiness')
            ->set_help_text('Select the type your business represents.');

        $fields[] = Field::make('complex', $field_prefix . '_default_opening_hours', 'Opening Hours')
            ->set_collapsed()
            ->help_text('Set default hours during which the business location is open.')
            ->add_fields($openingHoursCustomField->get_fields())
            ->set_header_template('
                      <%- day %> - <%- opens %> to <%- closes %>
                     ');

        $fields = array_merge($fields, [
            Field::make('separator', $field_prefix . '_gm_separator', 'Google Maps'),
            Field::make('text', $field_prefix . '_google_maps_api_key', __('Google Maps API Key (Browser - Javascript)'))
                ->set_default_value('')
                ->set_help_text('Google Maps API Key (browser) is needed for "Store Locator" & "Location Map".<p>You can get your API key here: <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">https://developers.google.com/maps/documentation/javascript/get-api-key</a></p><p>As of June 22, 2016, Google requires users to generate an API key in order to use the Maps API: <a href="https://developers.google.com/maps/pricing-and-plans/standard-plan-2016-update" target="_blank">https://developers.google.com/maps/pricing-and-plans/standard-plan-2016-update</a>.</p><p> You will also need to enable <a href="https://developers.google.com/places/web-service/overview" target="_blank">Google Places API</a> for store locator autocomplete searches.'),

            Field::make('text', $field_prefix . '_google_maps_api_geocoding_key', __('Google Maps API Key (Geocoding - Fetch lat/lng for csv imports)'))
                ->set_attribute('type', 'password')
                ->set_default_value('')
                ->set_help_text('Google Maps API Key is needed for geocoding. During CSV imports of locations, we fetch latitude and longitude geocoding information from google based on the address of the location.<br/><br/>Why do we need a separate API key? <br/>When you restrictThe Google Maps API key for browser - <a href="https://developers.google.com/maps/api-security-best-practices" target="blank">https://developers.google.com/maps/api-security-best-practices</a>, and try to geocode using the same API key, google will block the request. To overcome this issue, we recommend creating a separate API key with no restriction. Please don\'t share this key with anyone.'),

            Field::make('image', $field_prefix . '_custom_marker', __('Custom Marker Icon'))
                ->set_help_text('Upload a 100px x 100px image for the map marker. It overrides the default google maps marker icon.')
                ->set_value_type('url'),

            Field::make('separator', $field_prefix . '_multi_loc_separator', 'Locations "Post Type" & Category'),
            Field::make('checkbox', $field_prefix . '_make_locations_private', __('Make Locations "Post Type" Private'))
                ->set_help_text('Set to checked if you dont want to show individual location pages on your website')
                ->set_option_value('yes'),

            Field::make('association', $field_prefix . '_default_location', __('Default Location'))
                ->set_min(1)
                ->set_max(1)
                ->set_help_text('Select a default location. The Location schema will be shown on the homepage.')
                ->set_conditional_logic([
                    [
                        'field'   => $field_prefix . '_make_locations_private',
                        'value'   => true,
                        'compare' => '=',
                    ],
                ])
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'wpt-mlsl-locations',
                    ],
                ]),

            Field::make('text', $field_prefix . '_locations_slug', 'Locations Slug')
                ->set_required(true)
                ->set_help_text('Slug for the locations page')
                ->set_default_value('locations'),
            Field::make('text', $field_prefix . '_locations_label_singular', 'Locations Label - Singular')
                ->set_required(true)
                ->set_help_text('Singular label for locations post type')
                ->set_default_value('Location'),
            Field::make('text', $field_prefix . '_locations_label_plural', 'Locations Label - Plural')
                ->set_required(true)
                ->set_help_text('Plural label for locations post type')
                ->set_default_value('Locations'),
            // category fields
            Field::make('text', $field_prefix . '_locations_cat_slug', 'Location Categories Slug')
                ->set_required(true)
                ->set_help_text('Slug for the location category page')
                ->set_default_value('location-categories'),

            Field::make('text', $field_prefix . '_locations_cat_label_singular', 'Locations Category Label - Singular')
                ->set_required(true)
                ->set_help_text('Singular label for locations categories')
                ->set_default_value('Location Category'),
            Field::make('text', $field_prefix . '_locations_cat_label_plural', 'Locations Category Label - Plural')
                ->set_required(true)
                ->set_help_text('Plural label for location categories')
                ->set_default_value('Location Categories'),
        ]);

        if (!$this->setupDone) {
            Container::make('theme_options', __('Locations & SEO'))
                ->set_page_parent('options-general.php')
                ->add_fields($fields);
            $this->setupDone = true;
        }

    }

    /**
     * Check the settings and load location post type if multiple locations are enabled.
     */
    public function check_and_load_post_type()
    {
        include_once $this->container['plugin_dir'] . '/post-types/wpt-mlsl-locations.php';
        include_once $this->container['plugin_dir'] . '/taxonomies/mlsl-location-cat.php';
    }

    /**
     * Google maps api key
     */
    public function crb_get_gmaps_api_key($current_key)
    {
        return carbon_get_theme_option('wpt_mlsl_settings_google_maps_api_key');
    }

    /**
     * Get the custom marker url
     */
    public function custom_marker_url()
    {
        return carbon_get_theme_option('wpt_mlsl_settings_custom_marker');
    }

    /**
     * Enqueue google maps
     */
    public function enqueue_google_maps(
        $parent_handle,
        $callback = ''
    ) {
        $url = sprintf('https://maps.googleapis.com/maps/api/js?key=%s&libraries=places,geometry&callback=%s', $this->crb_get_gmaps_api_key(''), $callback);
        wp_enqueue_script('wpt-mlsl-google-maps', $url, [$parent_handle], false, false);
    }

    public function get_counties_options()
    {
        return [
            ''   => '-- Select One --',
            'AX' => __('Åland Islands', 'seo-for-local'),
            'AF' => __('Afghanistan', 'seo-for-local'),
            'AL' => __('Albania', 'seo-for-local'),
            'DZ' => __('Algeria', 'seo-for-local'),
            'AD' => __('Andorra', 'seo-for-local'),
            'AO' => __('Angola', 'seo-for-local'),
            'AI' => __('Anguilla', 'seo-for-local'),
            'AQ' => __('Antarctica', 'seo-for-local'),
            'AG' => __('Antigua and Barbuda', 'seo-for-local'),
            'AR' => __('Argentina', 'seo-for-local'),
            'AM' => __('Armenia', 'seo-for-local'),
            'AW' => __('Aruba', 'seo-for-local'),
            'AU' => __('Australia', 'seo-for-local'),
            'AT' => __('Austria', 'seo-for-local'),
            'AZ' => __('Azerbaijan', 'seo-for-local'),
            'BS' => __('Bahamas', 'seo-for-local'),
            'BH' => __('Bahrain', 'seo-for-local'),
            'BD' => __('Bangladesh', 'seo-for-local'),
            'BB' => __('Barbados', 'seo-for-local'),
            'BY' => __('Belarus', 'seo-for-local'),
            'PW' => __('Belau', 'seo-for-local'),
            'BE' => __('Belgium', 'seo-for-local'),
            'BZ' => __('Belize', 'seo-for-local'),
            'BJ' => __('Benin', 'seo-for-local'),
            'BM' => __('Bermuda', 'seo-for-local'),
            'BT' => __('Bhutan', 'seo-for-local'),
            'BO' => __('Bolivia', 'seo-for-local'),
            'BQ' => __('Bonaire, Sint Eustatius and Saba', 'seo-for-local'),
            'BA' => __('Bosnia and Herzegovina', 'seo-for-local'),
            'BW' => __('Botswana', 'seo-for-local'),
            'BV' => __('Bouvet Island', 'seo-for-local'),
            'BR' => __('Brazil', 'seo-for-local'),
            'IO' => __('British Indian Ocean Territory', 'seo-for-local'),
            'VG' => __('British Virgin Islands', 'seo-for-local'),
            'BN' => __('Brunei', 'seo-for-local'),
            'BG' => __('Bulgaria', 'seo-for-local'),
            'BF' => __('Burkina Faso', 'seo-for-local'),
            'BI' => __('Burundi', 'seo-for-local'),
            'KH' => __('Cambodia', 'seo-for-local'),
            'CM' => __('Cameroon', 'seo-for-local'),
            'CA' => __('Canada', 'seo-for-local'),
            'CV' => __('Cape Verde', 'seo-for-local'),
            'KY' => __('Cayman Islands', 'seo-for-local'),
            'CF' => __('Central African Republic', 'seo-for-local'),
            'TD' => __('Chad', 'seo-for-local'),
            'CL' => __('Chile', 'seo-for-local'),
            'CN' => __('China', 'seo-for-local'),
            'CX' => __('Christmas Island', 'seo-for-local'),
            'CC' => __('Cocos (Keeling) Islands', 'seo-for-local'),
            'CO' => __('Colombia', 'seo-for-local'),
            'KM' => __('Comoros', 'seo-for-local'),
            'CG' => __('Congo (Brazzaville)', 'seo-for-local'),
            'CD' => __('Congo (Kinshasa)', 'seo-for-local'),
            'CK' => __('Cook Islands', 'seo-for-local'),
            'CR' => __('Costa Rica', 'seo-for-local'),
            'HR' => __('Croatia', 'seo-for-local'),
            'CU' => __('Cuba', 'seo-for-local'),
            'CW' => __('Curaçao', 'seo-for-local'),
            'CY' => __('Cyprus', 'seo-for-local'),
            'CZ' => __('Czech Republic', 'seo-for-local'),
            'DK' => __('Denmark', 'seo-for-local'),
            'DJ' => __('Djibouti', 'seo-for-local'),
            'DM' => __('Dominica', 'seo-for-local'),
            'DO' => __('Dominican Republic', 'seo-for-local'),
            'EC' => __('Ecuador', 'seo-for-local'),
            'EG' => __('Egypt', 'seo-for-local'),
            'SV' => __('El Salvador', 'seo-for-local'),
            'GQ' => __('Equatorial Guinea', 'seo-for-local'),
            'ER' => __('Eritrea', 'seo-for-local'),
            'EE' => __('Estonia', 'seo-for-local'),
            'ET' => __('Ethiopia', 'seo-for-local'),
            'FK' => __('Falkland Islands', 'seo-for-local'),
            'FO' => __('Faroe Islands', 'seo-for-local'),
            'FJ' => __('Fiji', 'seo-for-local'),
            'FI' => __('Finland', 'seo-for-local'),
            'FR' => __('France', 'seo-for-local'),
            'GF' => __('French Guiana', 'seo-for-local'),
            'PF' => __('French Polynesia', 'seo-for-local'),
            'TF' => __('French Southern Territories', 'seo-for-local'),
            'GA' => __('Gabon', 'seo-for-local'),
            'GM' => __('Gambia', 'seo-for-local'),
            'GE' => __('Georgia', 'seo-for-local'),
            'DE' => __('Germany', 'seo-for-local'),
            'GH' => __('Ghana', 'seo-for-local'),
            'GI' => __('Gibraltar', 'seo-for-local'),
            'GR' => __('Greece', 'seo-for-local'),
            'GL' => __('Greenland', 'seo-for-local'),
            'GD' => __('Grenada', 'seo-for-local'),
            'GP' => __('Guadeloupe', 'seo-for-local'),
            'GT' => __('Guatemala', 'seo-for-local'),
            'GG' => __('Guernsey', 'seo-for-local'),
            'GN' => __('Guinea', 'seo-for-local'),
            'GW' => __('Guinea-Bissau', 'seo-for-local'),
            'GY' => __('Guyana', 'seo-for-local'),
            'HT' => __('Haiti', 'seo-for-local'),
            'HM' => __('Heard Island and McDonald Islands', 'seo-for-local'),
            'HN' => __('Honduras', 'seo-for-local'),
            'HK' => __('Hong Kong', 'seo-for-local'),
            'HU' => __('Hungary', 'seo-for-local'),
            'IS' => __('Iceland', 'seo-for-local'),
            'IN' => __('India', 'seo-for-local'),
            'ID' => __('Indonesia', 'seo-for-local'),
            'IR' => __('Iran', 'seo-for-local'),
            'IQ' => __('Iraq', 'seo-for-local'),
            'IM' => __('Isle of Man', 'seo-for-local'),
            'IL' => __('Israel', 'seo-for-local'),
            'IT' => __('Italy', 'seo-for-local'),
            'CI' => __('Ivory Coast', 'seo-for-local'),
            'JM' => __('Jamaica', 'seo-for-local'),
            'JP' => __('Japan', 'seo-for-local'),
            'JE' => __('Jersey', 'seo-for-local'),
            'JO' => __('Jordan', 'seo-for-local'),
            'KZ' => __('Kazakhstan', 'seo-for-local'),
            'KE' => __('Kenya', 'seo-for-local'),
            'KI' => __('Kiribati', 'seo-for-local'),
            'KW' => __('Kuwait', 'seo-for-local'),
            'KG' => __('Kyrgyzstan', 'seo-for-local'),
            'LA' => __('Laos', 'seo-for-local'),
            'LV' => __('Latvia', 'seo-for-local'),
            'LB' => __('Lebanon', 'seo-for-local'),
            'LS' => __('Lesotho', 'seo-for-local'),
            'LR' => __('Liberia', 'seo-for-local'),
            'LY' => __('Libya', 'seo-for-local'),
            'LI' => __('Liechtenstein', 'seo-for-local'),
            'LT' => __('Lithuania', 'seo-for-local'),
            'LU' => __('Luxembourg', 'seo-for-local'),
            'MO' => __('Macao S.A.R., China', 'seo-for-local'),
            'MK' => __('Macedonia', 'seo-for-local'),
            'MG' => __('Madagascar', 'seo-for-local'),
            'MW' => __('Malawi', 'seo-for-local'),
            'MY' => __('Malaysia', 'seo-for-local'),
            'MV' => __('Maldives', 'seo-for-local'),
            'ML' => __('Mali', 'seo-for-local'),
            'MT' => __('Malta', 'seo-for-local'),
            'MH' => __('Marshall Islands', 'seo-for-local'),
            'MQ' => __('Martinique', 'seo-for-local'),
            'MR' => __('Mauritania', 'seo-for-local'),
            'MU' => __('Mauritius', 'seo-for-local'),
            'YT' => __('Mayotte', 'seo-for-local'),
            'MX' => __('Mexico', 'seo-for-local'),
            'FM' => __('Micronesia', 'seo-for-local'),
            'MD' => __('Moldova', 'seo-for-local'),
            'MC' => __('Monaco', 'seo-for-local'),
            'MN' => __('Mongolia', 'seo-for-local'),
            'ME' => __('Montenegro', 'seo-for-local'),
            'MS' => __('Montserrat', 'seo-for-local'),
            'MA' => __('Morocco', 'seo-for-local'),
            'MZ' => __('Mozambique', 'seo-for-local'),
            'MM' => __('Myanmar', 'seo-for-local'),
            'NA' => __('Namibia', 'seo-for-local'),
            'NR' => __('Nauru', 'seo-for-local'),
            'NP' => __('Nepal', 'seo-for-local'),
            'NL' => __('Netherlands', 'seo-for-local'),
            'AN' => __('Netherlands Antilles', 'seo-for-local'),
            'NC' => __('New Caledonia', 'seo-for-local'),
            'NZ' => __('New Zealand', 'seo-for-local'),
            'NI' => __('Nicaragua', 'seo-for-local'),
            'NE' => __('Niger', 'seo-for-local'),
            'NG' => __('Nigeria', 'seo-for-local'),
            'NU' => __('Niue', 'seo-for-local'),
            'NF' => __('Norfolk Island', 'seo-for-local'),
            'KP' => __('North Korea', 'seo-for-local'),
            'NO' => __('Norway', 'seo-for-local'),
            'OM' => __('Oman', 'seo-for-local'),
            'PK' => __('Pakistan', 'seo-for-local'),
            'PS' => __('Palestinian Territory', 'seo-for-local'),
            'PA' => __('Panama', 'seo-for-local'),
            'PG' => __('Papua New Guinea', 'seo-for-local'),
            'PY' => __('Paraguay', 'seo-for-local'),
            'PE' => __('Peru', 'seo-for-local'),
            'PH' => __('Philippines', 'seo-for-local'),
            'PN' => __('Pitcairn', 'seo-for-local'),
            'PL' => __('Poland', 'seo-for-local'),
            'PT' => __('Portugal', 'seo-for-local'),
            'QA' => __('Qatar', 'seo-for-local'),
            'IE' => __('Republic of Ireland', 'seo-for-local'),
            'RE' => __('Reunion', 'seo-for-local'),
            'RO' => __('Romania', 'seo-for-local'),
            'RU' => __('Russia', 'seo-for-local'),
            'RW' => __('Rwanda', 'seo-for-local'),
            'ST' => __('São Tomé and Príncipe', 'seo-for-local'),
            'BL' => __('Saint Barthélemy', 'seo-for-local'),
            'SH' => __('Saint Helena', 'seo-for-local'),
            'KN' => __('Saint Kitts and Nevis', 'seo-for-local'),
            'LC' => __('Saint Lucia', 'seo-for-local'),
            'SX' => __('Saint Martin (Dutch part)', 'seo-for-local'),
            'MF' => __('Saint Martin (French part)', 'seo-for-local'),
            'PM' => __('Saint Pierre and Miquelon', 'seo-for-local'),
            'VC' => __('Saint Vincent and the Grenadines', 'seo-for-local'),
            'SM' => __('San Marino', 'seo-for-local'),
            'SA' => __('Saudi Arabia', 'seo-for-local'),
            'SN' => __('Senegal', 'seo-for-local'),
            'RS' => __('Serbia', 'seo-for-local'),
            'SC' => __('Seychelles', 'seo-for-local'),
            'SL' => __('Sierra Leone', 'seo-for-local'),
            'SG' => __('Singapore', 'seo-for-local'),
            'SK' => __('Slovakia', 'seo-for-local'),
            'SI' => __('Slovenia', 'seo-for-local'),
            'SB' => __('Solomon Islands', 'seo-for-local'),
            'SO' => __('Somalia', 'seo-for-local'),
            'ZA' => __('South Africa', 'seo-for-local'),
            'GS' => __('South Georgia/Sandwich Islands', 'seo-for-local'),
            'KR' => __('South Korea', 'seo-for-local'),
            'SS' => __('South Sudan', 'seo-for-local'),
            'ES' => __('Spain', 'seo-for-local'),
            'LK' => __('Sri Lanka', 'seo-for-local'),
            'SD' => __('Sudan', 'seo-for-local'),
            'SR' => __('Suriname', 'seo-for-local'),
            'SJ' => __('Svalbard and Jan Mayen', 'seo-for-local'),
            'SZ' => __('Swaziland', 'seo-for-local'),
            'SE' => __('Sweden', 'seo-for-local'),
            'CH' => __('Switzerland', 'seo-for-local'),
            'SY' => __('Syria', 'seo-for-local'),
            'TW' => __('Taiwan', 'seo-for-local'),
            'TJ' => __('Tajikistan', 'seo-for-local'),
            'TZ' => __('Tanzania', 'seo-for-local'),
            'TH' => __('Thailand', 'seo-for-local'),
            'TL' => __('Timor-Leste', 'seo-for-local'),
            'TG' => __('Togo', 'seo-for-local'),
            'TK' => __('Tokelau', 'seo-for-local'),
            'TO' => __('Tonga', 'seo-for-local'),
            'TT' => __('Trinidad and Tobago', 'seo-for-local'),
            'TN' => __('Tunisia', 'seo-for-local'),
            'TR' => __('Turkey', 'seo-for-local'),
            'TM' => __('Turkmenistan', 'seo-for-local'),
            'TC' => __('Turks and Caicos Islands', 'seo-for-local'),
            'TV' => __('Tuvalu', 'seo-for-local'),
            'UG' => __('Uganda', 'seo-for-local'),
            'UA' => __('Ukraine', 'seo-for-local'),
            'AE' => __('United Arab Emirates', 'seo-for-local'),
            'GB' => __('United Kingdom', 'seo-for-local'),
            'US' => __('United States', 'seo-for-local'),
            'UY' => __('Uruguay', 'seo-for-local'),
            'UZ' => __('Uzbekistan', 'seo-for-local'),
            'VU' => __('Vanuatu', 'seo-for-local'),
            'VA' => __('Vatican', 'seo-for-local'),
            'VE' => __('Venezuela', 'seo-for-local'),
            'VN' => __('Vietnam', 'seo-for-local'),
            'WF' => __('Wallis and Futuna', 'seo-for-local'),
            'EH' => __('Western Sahara', 'seo-for-local'),
            'WS' => __('Western Samoa', 'seo-for-local'),
            'YE' => __('Yemen', 'seo-for-local'),
            'ZM' => __('Zambia', 'seo-for-local'),
            'ZW' => __('Zimbabwe', 'seo-for-local'),
        ];
    }

}
