<?php
namespace WPT\MLSL\WP;

/**
 * GoogleMap.
 */
class GoogleMap
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function wp_enqueue_scripts()
    {
        if (is_admin()) {
            $this->dequeue_scripts();
        }
    }

    /**
     * Dequeue divi google maps scripts.
     */
    public function dequeue_scripts()
    {
        wp_dequeue_script('google-maps-api');
        wp_dequeue_script('et_bfb_google_maps_api');
    }

    /**
     * Google maps geocoding
     */
    public function geocode($address)
    {

        // url encode the address
        $address = rawurlencode($address);

        // google map geocode api url
        $url = sprintf("https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s", $address, $this->container['admin_settings']->google_maps_geocoding_key());

        // get the json response
        $resp_json = wp_remote_get($url);

        // decode the json
        $resp = json_decode($resp_json, true);

        // response status will be 'OK', if able to geocode given address
        if (is_array($resp) && isset($resp['status']) && ($resp['status'] == 'OK')) {
            $lat               = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
            $lng               = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
            $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";

            if ($lat && $lng) {
                $response = [
                    'lat' => $lat,
                    'lng' => $lng,
                ];

                if ($formatted_address) {
                    $response['formatted_address'] = $formatted_address;
                }

                return $response;

            } else {
                return ['error' => 'Geocoding failed.'];
            }

        } else {
            return ['error' => $resp['status']];
        }
    }

}
