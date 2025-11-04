<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\console
 */
namespace fractalCms\console;

use fractalCms\components\Constant;
use yii\console\Controller;
use Exception;
use Yii;
use yii\console\ExitCode;
use yii\rbac\Permission;
use yii\rbac\Role;

class RbacController extends Controller
{
    /**
     * Init Rabc permissions
     *
     * @return int
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        try {
            $this->stdout('rbac init');
            $auth = Yii::$app->authManager;
            //ROLES
            $admin = $auth->getRole(Constant::ROLE_ADMIN);
            if ($admin === null) {
                $admin = $auth->createRole(Constant::ROLE_ADMIN);
                $auth->add($admin);
            }
            $author = $auth->getRole(Constant::ROLE_AUTHOR);
            if ($author === null) {
                $author = $auth->createRole(Constant::ROLE_AUTHOR);
                $auth->add($author);
                $auth->addChild($admin, $author);
            }
            //PERMISSION
            $main = [
                Constant::PERMISSION_MAIN_USER,
                Constant::PERMISSION_MAIN_CONTENT,
                Constant::PERMISSION_MAIN_ITEM,
                Constant::PERMISSION_MAIN_TAG,
                Constant::PERMISSION_MAIN_MENU,
                Constant::PERMISSION_MAIN_PARAMETER,
                Constant::PERMISSION_MAIN_CONTENT_ITEM,
                Constant::PERMISSION_MAIN_TAG_ITEM,
                Constant::PERMISSION_MAIN_CONTENT_TAG,
                Constant::PERMISSION_MAIN_CONFIG_TYPE,
                Constant::PERMISSION_MAIN_CONFIG_ITEM,
            ];

            $actions = [
                Constant::PERMISSION_ACTION_CREATE,
                Constant::PERMISSION_ACTION_UPDATE,
                Constant::PERMISSION_ACTION_DELETE,
                Constant::PERMISSION_ACTION_ACTIVATION,
                Constant::PERMISSION_ACTION_LIST,
            ];

            foreach ($main as $permissionMain) {
                $permissionManageName = $permissionMain.Constant::PERMISSION_ACTION_MANAGE;
                /** @var Permission $permissionManage */
                $permissionManage = $auth->getPermission($permissionManageName);
                if ($permissionManage === null) {
                    $permissionManage = $auth->createPermission($permissionManageName);
                    $auth->add($permissionManage);
                    if ($admin instanceof Role) {
                        $auth->addChild($admin, $permissionManage);
                    }
                 }

                foreach ($actions as $action) {
                    $permissionName = $permissionMain.$action;
                    $permission = $auth->getPermission($permissionName);
                    if ($permission === null) {
                        $permission = $auth->createPermission($permissionName);
                        $auth->add($permission);
                    }
                    $hasChild = $auth->hasChild($permissionManage, $permission);
                    if ($hasChild === false) {
                        $auth->addChild($permissionManage, $permission);
                    }
                }

            }


            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
