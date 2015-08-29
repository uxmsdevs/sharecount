<?php

return [
    'app' => [
        'name'          => 'Share Count',
        'desc'          => 'Share Count plugin can obtain sharing count of an URL on Facebook/Twitter/Google+ Platforms',
        'setting_desc'  => 'Configure timeout and CRON Settings',
        'menu_label'    => 'Share Count'
    ],

    'generic' => [
        'return_relations' => 'Return to the list',
        'check_configs' => "Count can't be fetched"
    ],

    'settings' => [
        'cache_time_out' => [
            'title' => 'Cache Timeout',
            'desc'  => 'The time for caching the count of social networks sharings for every webpage you defined. (We recommend minimum Daily..)'
        ],
        'fetch_with_cron' => [
            'title' => 'Fetch with CRON',
            'desc'  => 'If you activated CRON, sharing counts will be updated in background. Else, when a visitor opened a page which has Share Count component, statistics will be updated. You need to set-up October CMS Cron for this functionality.'
        ],
        'timezone' => [
            'title' => 'Server Timezone',
            'desc'  => 'Please choose right Timezone from this box for correct results'
        ]
    ],

    'webpage' => [
        'title' => 'Webpage to Show Count',
        'detail' => 'Select a webpage to show sharing counts'
    ],

    'addresses' => [
        'statistics' => 'Statistics',
        'page_title' => 'Webpages',
        'page_title_one' => 'Webpage',
        'url' => [
            'form_title' => 'URL Address',
            'title' => 'Webpage URL',
            'desc'  => 'Webpage URL for checking'
        ],
        'count_face' => [
            'form_title' => 'Facebook Count',
            'title' => 'Last Facebook Count',
            'desc'  => '(Informational Field)'
        ],
        'count_twit' => [
            'form_title' => 'Twitter Count',
            'title' => 'Last Twitter Count',
            'desc'  => '(Informational Field)'
        ],
        'count_gp' => [
            'form_title' => 'Google+ Count',
            'title' => 'Last Google+ Count',
            'desc'  => '(Informational Field)'
        ],
        'last_fetched' => [
            'form_title' => 'Last Fetch Time',
            'title' => 'Last Fetched Time',
            'desc'  => 'Last fetched time of the counts. (Time is based on "Timezone Option" in settings section.)'
        ]
    ],
];
