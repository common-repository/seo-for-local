<?php
namespace WPT\MLSL\Locations;

/**
 * Locations.
 */
class Locations
{
    protected $container;
    protected $locations;
    protected $default_marker_icon;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;

        $this->locations           = [];
        $this->default_marker_icon = $container['admin_settings']->get_default_marker();
    }

    /**
     * Get complete details of a location with post meta
     * Can be used in store locator, single map and structured data
     */
    public function get($location)
    {
        if (!isset($location->ID)) {
            return [];
        }

        if (isset($this->locations[$location->ID])) {
            return $this->locations[$location->ID];
        }

        $meta       = get_post_meta($location->ID);
        $categories = get_the_terms($location->ID, 'mlsl-location-cat');
        $cat_ids    = [];
        if ($categories && is_array($categories)) {
            foreach ($categories as $category) {
                $cat_ids[] = $category->term_id;
            }
        }

        $data = [
            'id'         => $location->ID,
            'title'      => $location->post_title,
            'excerpt'    => $location->post_excerpt,
            'categories' => $cat_ids,
            'url'        => get_the_permalink($location->ID),
        ];

        $keys = [
            '_mlsl_country'        => 'country',
            '_mlsl_fax'            => 'fax',
            '_mlsl_locality'       => 'locality',
            '_mlsl_map|||0|lat'    => 'lat',
            '_mlsl_map|||0|lng'    => 'lng',
            '_mlsl_map|||0|zoom'   => 'zoom',
            '_mlsl_postal_code'    => 'postal_code',
            '_mlsl_price'          => 'price',
            '_mlsl_region'         => 'region',
            '_mlsl_street_address' => 'street_address',
            '_mlsl_taxid'          => 'tax_id',
            '_mlsl_tel'            => 'tel',
            '_mlsl_vat'            => 'vat',
            '_mlsl_email'          => 'email',
            '_mlsl_marker'         => 'marker_icon',
            '_mlsl_business_type'  => 'business_type',
        ];

        foreach ($keys as $key => $value) {
            if (isset($meta[$key], $meta[$key][0])) {
                $data[$value] = $meta[$key][0];
            } else {
                $data[$value] = '';
            }
        }

        if (isset($data['marker_icon']) && $data['marker_icon']) {
            $data['marker_icon'] = wp_get_attachment_image_url($data['marker_icon'], 'full');
        } else {
            if ($this->default_marker_icon) {
                $data['marker_icon'] = $this->default_marker_icon;
            } else {
                unset($data['marker_icon']);
            }
        }

        $opening_hours = $this->get_opening_hours($location->ID);

        $data['opening_hours']     = $opening_hours;
        $data['formatted_address'] = $this->get_formatted_address($data);

        $attachment_id = get_post_thumbnail_id($location->ID);

        if ($attachment_id) {
            $data['image'] = wp_get_attachment_image_url($attachment_id, 'medium');
        }

        $this->locations[$location->ID] = $data;

        return $this->locations[$location->ID];
    }

    /**
     * Format opening hours.
     */
    public function get_opening_hours($location_id)
    {
        $opening_hours = carbon_get_post_meta($location_id, 'mlsl_opening_hours');
        $output        = [];

        if (is_array($opening_hours) && !empty($opening_hours)) {
            foreach ($opening_hours as $opening_hour) {
                $output[] = [
                    'day'    => $opening_hour['day'],
                    'opens'  => $opening_hour['opens'],
                    'closes' => $opening_hour['closes'],
                ];
            }
        }

        return $output;
    }

    /**
     * Get formatted address
     */
    public function get_formatted_address($location)
    {
        $address = $this->get_address_array($location);

        return implode(', ', $address);
    }

    /**
     * Get address array.
     */
    public function get_address_array($location)
    {
        $address = [];

        if (isset($location['street_address']) && trim($location['street_address'])) {
            $address[] = trim($location['street_address']);
        }

        if (isset($location['locality']) && trim($location['locality'])) {
            $address[] = trim($location['locality']);
        }

        if (isset($location['region']) && trim($location['region'])) {
            $address[] = trim($location['region']);
        }

        if (isset($location['postal_code']) && trim($location['postal_code'])) {
            $address[] = trim($location['postal_code']);
        }

        if (isset($location['country']) && trim($location['country'])) {
            $address[] = trim($location['country']);
        }

        return $address;
    }

}
