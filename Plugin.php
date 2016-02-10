<?php namespace Uxms\Sharecount;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Illuminate\Foundation\AliasLoader;
use Uxms\Sharecount\Models\Configs;
use Uxms\Sharecount\Components\ShareCount;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'uxms.sharecount::lang.app.name',
            'description' => 'uxms.sharecount::lang.app.desc',
            'author'      => 'uXMs Devs',
            'icon'        => 'icon-group',
            'homepage'    => 'https://uxms.net/'
        ];
    }

    public function registerPermissions()
    {
        return [
            'uxms.sharecount.address' => [
                'label' => 'Access Webpages',
                'tab' => 'Share Count'
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'Uxms\Sharecount\Components\ShareCount' => 'shareCount'
        ];
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'uxms.sharecount::lang.app.name',
                'description' => 'uxms.sharecount::lang.app.setting_desc',
                'icon'        => 'icon-group',
                'class'       => 'Uxms\Sharecount\Models\Configs',
                'category'    => SettingsManager::CATEGORY_SOCIAL,
                'order'       => 998
            ]
        ];
    }

    /**
     * Not yet working in v1.0.0
     */
    public function registerSchedule($schedule)
    {
        $schedule->call(function() {
            ShareCount::instance()->updateShareCounts();
        })->everyFiveMinutes();
    }

    public function registerNavigation()
    {
        return [
            'sharecount' => [
                'label'       => 'uxms.sharecount::lang.app.menu_label',
                'url'         => Backend::url('uxms/sharecount/address'),
                'icon'        => 'icon-group',
                'permissions' => ['uxms.sharecount.*'],
                'order'       => 998,

                'sideMenu' => [
                    'address' => [
                        'label'       => 'uxms.sharecount::lang.addresses.page_title',
                        'icon'        => 'icon-connectdevelop',
                        'url'         => Backend::url('uxms/sharecount/address'),
                        'permissions' => ['uxms.sharecount.address']
                    ]
                ]
            ]
        ];
    }

    /**
     * The boot() method is called right before a request is routed
     */
    public function boot()
    {
        if ( Configs::get('cache_time_out') == null ) {
            Configs::set('cache_time_out', 'daily');
        }

        if ( Configs::get('fetch_with_cron') == null ) {
            Configs::set('fetch_with_cron', '1');
        }

        if ( Configs::get('timezone') == null ) {
            Configs::set('timezone', 'America/Los_Angeles');
        }

        date_default_timezone_set(Configs::get('timezone'));
    }

}
