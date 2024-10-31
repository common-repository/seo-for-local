<?php

if ( !function_exists( 'wpt_mlsl' ) ) {
    // Create a helper function for easy SDK access.
    function wpt_mlsl()
    {
        global  $wpt_mlsl ;
        
        if ( !isset( $wpt_mlsl ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wpt_mlsl = fs_dynamic_init( [
                'id'             => '9289',
                'slug'           => 'seo-for-local',
                'type'           => 'plugin',
                'public_key'     => 'pk_20d3b7191c2cbf6d8c6ca727d7125',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => [
                'days'               => 7,
                'is_require_payment' => false,
            ],
                'menu'           => [
                'slug'    => 'edit.php?post_type=wpt-mlsl-locations',
                'support' => false,
            ],
                'is_live'        => true,
            ] );
        }
        
        return $wpt_mlsl;
    }
    
    // Init Freemius.
    wpt_mlsl();
    // Signal that SDK was initiated.
    do_action( 'wpt_mlsl_loaded' );
}
