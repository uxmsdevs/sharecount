<?php namespace Uxms\Sharecount\Controllers;

use Flash;
use BackendMenu;
use Backend\Classes\Controller;
use Uxms\Sharecount\Models\Addresses;


class Address extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $requiredPermissions = ['uxms.sharecount.address'];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Uxms.Sharecount', 'sharecount', 'address');
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $relationId) {
                if (!$role = Addresses::find($relationId))
                    continue;

                $role->delete();
            }

            Flash::success('URL has been deleted successfully.');
        }

        return $this->listRefresh();
    }

}
