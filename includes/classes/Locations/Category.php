<?php
namespace WPT\MLSL\Locations;

/**
 * Category.
 */
class Category
{
    protected $container;

    protected $categories;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container  = $container;
        $this->categories = [];
    }

    /**
     * Get category for the location
     */
    public function get_categories($args)
    {
        $args['taxonomy']   = 'mlsl-location-cat';
        $args['hide_empty'] = true;
        $args['number']     = 0;
        return get_terms($args);
    }

    /**
     * Find a category or create one.
     */
    public function find_or_create($category_name)
    {
        return wp_create_term($category_name, 'mlsl-location-cat');
    }

}
