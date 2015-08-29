<?php namespace Uxms\Sharecount\Components;

use Lang;
use Cms\Classes\ComponentBase;
use Uxms\Sharecount\Models\Configs;
use Uxms\Sharecount\Models\Addresses;

class ShareCount extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'          => 'uxms.sharecount::lang.app.name',
            'description'   => 'uxms.sharecount::lang.app.desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'webpage' => [
                'title'       => 'uxms.sharecount::lang.webpage.title',
                'description' => 'uxms.sharecount::lang.webpage.detail',
                'type'        => 'dropdown'
            ]
        ];
    }

    /**
     * Holds the time of now
     */
    public $date_now;

    /**
     * Holds the URL's Data
     */
    public $urlData;

    /**
     * Platform specific counts
     */
    public $count_face;
    public $count_twit;
    public $count_gp;

    /**
     * A list of all created webpages
     *
     * @return array
     */
    public function getWebpageOptions()
    {
        $allWebpages = Addresses::all();

        $webpageOpts[0] = '-- Please Select--';
        foreach ($allWebpages as $value) {
            $webpageOpts[$value['id']] = $value['url'];
        }

        return $webpageOpts;
    }

    /**
     * Starter method of the component.
     *
     * @return string
     */
    public function onRun()
    {
        $this->date_now = date("Y-m-d H:i:s");

        if ($this->property('webpage') > 0) {
            $urlObj = Addresses::where('id', '=', $this->property('webpage'));
            $this->urlData = $urlObj->first();

            if ($this->needUpdateCount()) {
                $this->fetchFacebook();
                $this->fetchTwitter();
                $this->fetchGoogleplus();

                $updateCurrentsTime = $urlObj->first();
                $updateCurrentsTime->last_fetched = $this->date_now;
                $updateCurrentsTime->save();
            }
        }
    }

    /**
     * Updates the Social Sharing counts with Crontab
     *
     * @return boolean
     */
    public function updateShareCounts()
    {
        if (Configs::get('fetch_with_cron')) {

        }

    }

    /**
     * Checks for if cache timeout
     *
     * @return boolean
     */
    public function needUpdateCount()
    {
        $timeVars = [
            'everyFiveMinutes'      => 5,
            'everyTenMinutes'       => 10,
            'everyThirtyMinutes'    => 30,
            'hourly'                => 60,
            'daily'                 => 1440,
            'weekly'                => 10080,
            'monthly'               => 43200,
            'yearly'                => 525600
        ];

        // $timeVars[ Configs::get('cache_time_out') ] * 60
        if ( time() - strtotime($this->urlData['last_fetched']) > $timeVars[ Configs::get('cache_time_out') ] * 60 ) {
            // lets update counts
            return true;
        } else {
            // no need to update yet
            return false;
        }
    }

    /**
     * Getting data via cURL
     *
     * @param $encUrl
     *
     * @return mixed
     */
    private function _parse($encUrl)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER  => true,    /* return web page */
            CURLOPT_HEADER          => false,   /* don't return headers */
            CURLOPT_FOLLOWLOCATION  => true,    /* follow redirects */
            CURLOPT_ENCODING        => "",      /* handle all encodings */
            CURLOPT_USERAGENT       => 'share', /* who am I ? */
            CURLOPT_AUTOREFERER     => true,    /* set referer on redirect */
            CURLOPT_CONNECTTIMEOUT  => 5,       /* timeout on connect */
            CURLOPT_TIMEOUT         => 10,      /* timeout on response */
            CURLOPT_MAXREDIRS       => 3,       /* stop after 3 redirects */
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_SSL_VERIFYPEER  => false,
        );
        $ch = curl_init();

        $options[CURLOPT_URL] = $encUrl;
        curl_setopt_array($ch, $options);

        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);

        curl_close($ch);

        return $content;
    }

    public function updateCurrentUrlDate()
    {
        $urlObj = Addresses::find($this->property('webpage'));
        $urlObj->last_fetched = $this->date_now;
        $urlObj->save();
    }

    public function facebook()
    {
        if ($this->property('webpage') == null || $this->property('webpage') <= 0) {
            return Lang::get('uxms.sharecount::lang.generic.check_configs');
        }
        if ($this->needUpdateCount()) {
            $this->updateCurrentUrlDate();
            return $this->fetchFacebook();
        } else {
            return $this->urlData['count_face'];
        }
    }

    public function twitter()
    {
        if ($this->property('webpage') == null || $this->property('webpage') <= 0) {
            return Lang::get('uxms.sharecount::lang.generic.check_configs');
        }
        if ($this->needUpdateCount()) {
            $this->updateCurrentUrlDate();
            return $this->fetchTwitter();
        } else {
            return $this->urlData['count_twit'];
        }
    }

    public function googleplus()
    {
        if ($this->property('webpage') == null || $this->property('webpage') <= 0) {
            return Lang::get('uxms.sharecount::lang.generic.check_configs');
        }
        if ($this->needUpdateCount()) {
            $this->updateCurrentUrlDate();
            return $this->fetchGoogleplus();
        } else {
            return $this->urlData['count_gp'];
        }
    }

    /**
     * Gets number of shares on Facebook
     *
     * @return int
     */
    private function fetchFacebook()
    {
        $paramsURL = self::_parse("http://graph.facebook.com/?id=".urlencode($this->urlData['url']));
        $paramsURLCount = json_decode($paramsURL, true);

        if (!isset($paramsURLCount['id'])) {
            return false;
        }

        $this->count_face = isset($paramsURLCount['shares']) ? $paramsURLCount['shares'] : 0;

        $facebookObj = Addresses::find($this->property('webpage'));
        $facebookObj->count_face = $this->count_face;
        $facebookObj->save();

        return $this->count_face;
    }

    /**
     * Gets number of shares on Twitter
     *
     * @return int
     */
    private function fetchTwitter()
    {
        $paramsURL = self::_parse("http://cdn.api.twitter.com/1/urls/count.json?url=".$this->urlData['url']);
        $paramsURLCount = json_decode($paramsURL, true);

        if (!isset($paramsURLCount['count'])) {
            return false;
        }

        $this->count_twit = $paramsURLCount['count'];

        $twitterObj = Addresses::find($this->property('webpage'));
        $twitterObj->count_twit = $this->count_twit;
        $twitterObj->save();

        return $this->count_twit;
    }

    /**
     * Gets number of shares on Google+
     *
     * @return int|bool
     */
    private function fetchGoogleplus()
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$this->urlData['url'].'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

        $curl_results = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($curl_results, true);
        $this->count_gp = isset($json[0]['result']['metadata']['globalCounts']['count'])
            ? intval($json[0]['result']['metadata']['globalCounts']['count'])
            : 0;

        $googleObj = Addresses::find($this->property('webpage'));
        $googleObj->count_gp = $this->count_gp;
        $googleObj->save();

        return $this->count_gp;
    }

}
