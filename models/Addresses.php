<?php namespace Uxms\Sharecount\Models;

use October\Rain\Database\Model;


class Addresses extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'uxms_sharecount_webpages';

    public $rules = [
        'url'  => 'required'
    ];

    protected $purgeable = ['last_fetched'];

    //public $dates = ['last_fetched'];
}
