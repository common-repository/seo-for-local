<?php

namespace WPT\MLSL\Locations;

/**
 * Api.
 */
class Api
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
     * Get category list for gutenberg block
     */
    public function categories_for_block( $request )
    {
        $categories = $this->container['location_category']->get_categories( [] );
        $options = [];
        foreach ( $categories as $category ) {
            $options[] = [
                'val'   => $category->term_id,
                'label' => $category->name,
            ];
        }
        return $options;
    }
    
    /**
     * Get location list for gutenberg block
     */
    public function locations_for_block( $request )
    {
        $locations = $this->container['locations_crud']->all( [] );
        $options[] = [
            'value' => '',
            'label' => '-- Select Location --',
        ];
        foreach ( $locations as $location ) {
            $options[] = [
                'value' => $location->ID,
                'label' => $location->post_title,
            ];
        }
        return $options;
    }

}