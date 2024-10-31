<?php
namespace WPT\MLSL\WP;

/**
 * Notify.
 */
class Notify
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function success($message)
    {
        return $this->message($message, 'success');

    }

    public function error($message)
    {
        return $this->message($message, 'error');
    }

    public function message(
        $message,
        $class = 'info'
    ) {
        return sprintf('<p class="%s">%s</p>', $class, $message);
    }

}
