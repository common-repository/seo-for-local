<?php

namespace WPT\MLSL\WP;

/**
 * Schema.
 */
class Schema
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
     * Load the schema.
     */
    public function load_schema()
    {
        // home page
        if ( isset( $_SERVER, $_SERVER['REQUEST_URI'] ) && $_SERVER['REQUEST_URI'] == '/' ) {
            
            if ( $this->container['admin_settings']->is_location_post_type_private() == 'yes' ) {
                $post_id = $this->container['admin_settings']->get_default_location_id();
                // phpcs:ignore
                echo  $this->load_location_schema( get_post( $post_id ), home_url( '/' ) ) ;
            }
        
        }
    }
    
    /**
     *
     */
    public function load_location_schema( $post_id, $url = '' )
    {
        $location = $this->container['locations']->get( $post_id );
        $data = [
            "@context" => "https://schema.org",
            "@type"    => ( isset( $location['business_type'] ) ? $location['business_type'] : 'LocalBusiness' ),
            'name'     => ( isset( $location['title'] ) ? $location['title'] : '' ),
            'url'      => ( $url ? $url : $location['url'] ),
        ];
        if ( isset( $location['image'] ) ) {
            $data['image'] = $location['image'];
        }
        if ( isset( $location['tel'] ) ) {
            $data['telephone'] = $location['tel'];
        }
        if ( isset( $location['price'] ) ) {
            $data['priceRange'] = $location['price'];
        }
        if ( isset( $location['lat'] ) ) {
            $data['geo'] = [
                "@type"     => "GeoCoordinates",
                "latitude"  => $location['lat'],
                "longitude" => $location['lng'],
            ];
        }
        
        if ( isset( $location['postal_code'] ) || isset( $location['region'] ) || isset( $location['street_address'] ) || isset( $location['country'] ) || isset( $location['locality'] ) || isset( $location['region'] ) ) {
            // can add address
            $address = [
                "@type" => "PostalAddress",
            ];
            if ( isset( $location['street_address'] ) ) {
                $address['streetAddress'] = $location['street_address'];
            }
            if ( isset( $location['locality'] ) ) {
                $address['addressLocality'] = $location['locality'];
            }
            if ( isset( $location['region'] ) ) {
                $address['addressRegion'] = $location['region'];
            }
            if ( isset( $location['postal_code'] ) ) {
                $address['postalCode'] = $location['postal_code'];
            }
            if ( isset( $location['country'] ) ) {
                $address['addressCountry'] = $location['country'];
            }
            $data['address'] = $address;
        }
        
        //address
        
        if ( isset( $location['opening_hours'] ) && !empty($location['opening_hours']) ) {
            $data['openingHoursSpecification'] = [];
            $opening_hours_data = [];
            foreach ( $location['opening_hours'] as $opening_hour ) {
                $key = sprintf( '%s|%s', $opening_hour['opens'], $opening_hour['closes'] );
                $opening_hours_data[$key][] = $opening_hour['day'];
            }
            foreach ( $opening_hours_data as $timing => $days_of_week ) {
                $open_close = explode( '|', $timing );
                if ( isset( $open_close[0] ) ) {
                    $opening_hour_schema_data = [
                        "@type"     => "OpeningHoursSpecification",
                        'dayOfWeek' => $days_of_week,
                        'opens'     => $open_close[0],
                    ];
                }
                if ( isset( $open_close[1] ) ) {
                    $opening_hour_schema_data['closes'] = $open_close[1];
                }
                $data['openingHoursSpecification'][] = $opening_hour_schema_data;
            }
        }
        
        //opening hours
        return sprintf( '<script type="application/ld+json">%s</script>', wp_json_encode( $data ) );
    }

}