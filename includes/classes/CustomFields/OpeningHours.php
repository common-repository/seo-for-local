<?php
namespace WPT\MLSL\CustomFields;

use Carbon_Fields\Field;

/**
 * OpeningHours.
 */
class OpeningHours
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
     * Get the fields for the complex field type
     */
    public function get_fields()
    {
        return [
            Field::make('select', 'day', 'Name')->help_text('Day of the week')
                ->set_options([
                    'Monday'    => 'Monday',
                    'Tuesday'   => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday'  => 'Thursday',
                    'Friday'    => 'Friday',
                    'Saturday'  => 'Saturday',
                    'Sunday'    => 'Sunday',
                ]),
            Field::make('time', 'opens', 'Opens At')->help_text('The opening hour of the place or service on the given day of the week.')
                ->set_picker_options(['time_24hr' => true, 'defaultHour' => '9'])
                ->set_input_format('H:i', 'H:i')
                ->set_storage_format('H:i'),
            Field::make('time', 'closes', 'Closes At')->help_text('The closing hour of the place or service on the given day of the week.')
                ->set_picker_options(['time_24hr' => true, 'defaultHour' => '17'])
                ->set_input_format('H:i', 'H:i')
                ->set_storage_format('H:i'),
        ];
    }

    /**
     * Set the header for each row
     */
    public function set_heading(&$complex_field)
    {
        $complex_field->set_header_template('
                      <%- day %> - <%- opens %> to <%- closes %>
                     ');
    }

    /**
     * Get the days of the week
     */
    public function get_days_of_week()
    {
        return [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];
    }

}
