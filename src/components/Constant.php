<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\components;

use fractalCms\models\User;
use Yii;
use Exception;

class Constant
{
    const PERMISSION_ACTION_MANAGE = 'MANAGE';
    const PERMISSION_ACTION_CREATE = 'CREATE';
    const PERMISSION_ACTION_UPDATE = 'UPDATE';
    const PERMISSION_ACTION_DELETE = 'DELETE';
    const PERMISSION_ACTION_ACTIVATION = 'ACTIVATION';
    const PERMISSION_ACTION_LIST = 'LIST';

    const PERMISSION_MAIN_USER = 'USER:';
    const PERMISSION_MAIN_CONTENT = 'CONTENT:';
    const PERMISSION_MAIN_ITEM = 'ITEM:';
    const PERMISSION_MAIN_MENU = 'MENU:';
    const PERMISSION_MAIN_PARAMETER = 'PARAMTEER:';
    const PERMISSION_MAIN_CONTENT_ITEM = 'CONTENT:ITEM:';
    const PERMISSION_MAIN_CONFIG_TYPE = 'CONFIG:TYPE:';
    const PERMISSION_MAIN_CONFIG_ITEM = 'CONFIG:ITEM:';

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_AUTHOR = 'AUTHOR';
    const TRACE_DEBUG = 'debug';

    public static $main = [
        Constant::PERMISSION_MAIN_USER => 'Utilisateur',
        Constant::PERMISSION_MAIN_CONTENT => 'Article',
        Constant::PERMISSION_MAIN_ITEM => 'Elément',
        Constant::PERMISSION_MAIN_MENU => 'Menu',
        Constant::PERMISSION_MAIN_CONFIG_TYPE => 'Configuration article',
        Constant::PERMISSION_MAIN_CONFIG_ITEM => 'Configuration élément',
        Constant::PERMISSION_MAIN_PARAMETER => 'Configuration Paramètres',
    ];

    public static $actions = [
        Constant::PERMISSION_ACTION_CREATE => 'Créer',
        Constant::PERMISSION_ACTION_UPDATE => 'Mettre à jour',
        Constant::PERMISSION_ACTION_DELETE => 'Supprimer',
        Constant::PERMISSION_ACTION_ACTIVATION => 'Activer / désactiver',
        Constant::PERMISSION_ACTION_LIST => 'Lister',
    ];

    public static function buildListRules(User $user) : array
    {
        try {
            $auth = Yii::$app->authManager;
            $rules = [];

            foreach (self::$main as $permissionMain => $title) {
                $permissionManageName = $permissionMain.Constant::PERMISSION_ACTION_MANAGE;
                $permissionManageTitle = $title.' Gestion';
                $manageRules = [
                    'id' => $permissionManageName,
                    'title' => $permissionManageTitle,
                    'value' => ($auth->checkAccess($user->id, $permissionManageName) === true) ? 1 : 0,
                    'children' => []
                ];

                foreach (self::$actions as $action => $titleAction) {
                    $permissionName = $permissionMain.$action;
                    $permissiontTitle = $title.' '.$titleAction;
                    $permisseChild = [
                        'id' => $permissionName,
                        'title' => $permissiontTitle,
                        'value' => ($auth->checkAccess($user->id, $permissionName) === true) ? 1 : 0,
                        'children' => null
                    ];
                    $manageRules['children'][$permissionName] = $permisseChild;
                }
                $rules[$permissionManageName] = $manageRules;
            }
            return $rules;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

}
