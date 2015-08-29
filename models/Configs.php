<?php namespace Uxms\Sharecount\Models;

use October\Rain\Database\Model;

/**
 * Uxms RESTful API Settings Model
 *
 * @package uxms\sharecount
 * @author Uxms Devs
 */
class Configs extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'uxms_sharecount_configs';
    public $settingsFields = 'fields.yaml';

    public function getCacheTimeOutOptions()
    {
        return [
            'everyFiveMinutes'      => 'Every 5 minutes',
            'everyTenMinutes'       => 'Every 10 minutes',
            'everyThirtyMinutes'    => 'Every 30 minutes',
            'hourly'                => 'Hourly',
            'daily'                 => 'Daily',
            'weekly'                => 'Weekly',
            'monthly'               => 'Monthly',
            'yearly'                => 'Yearly'
        ];
    }

    public function getTimezoneOptions()
    {
        $allTimeZones = [];
        foreach (\DateTimeZone::listIdentifiers() as $value) {
            $allTimeZones[$value] = $value;
        }
        return $allTimeZones;
    }


}
