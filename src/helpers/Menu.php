<?php
/**
 * Menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package cms/helpers
 */
namespace fractalCms\helpers;

use fractalCms\components\Constant;
use fractalCms\models\User;
use yii\base\Component;
use Exception;
use Yii;
use yii\helpers\Url;

class Menu extends Component
{

    public function get() : array
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            return $this->build();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Items => [
     *   [
     *      'title' => string,
     *      'url' => string,
     *      'icon' => string (svg),
     *      'optionsClass' => string,
     *      'children' => [] (array of items)
     *   ]
     * ]
     * @return array
     * @throws Exception
     */
    protected function build() : array
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            $data = [];
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'user') {
                    $optionsClass[] = 'text-primary fw-bold';
                }

                $data[] = [
                    'title' => 'Utilisateurs',
                    'url' => Url::to(['user/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_CONFIG_TYPE.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'config-type') {
                    $optionsClass[] = 'text-primary fw-bold';
                }

                $data[] = [
                    'title' => 'Configuration article type',
                    'url' => Url::to(['config-type/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_CONFIG_ITEM.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'config-item') {
                    $optionsClass[] = 'text-primary fw-bold';
                }

                $data[] = [
                    'title' => 'Configuration élément',
                    'url' => Url::to(['config-item/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }

            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'content') {
                    $optionsClass[] = 'text-primary fw-bold';
                }

                $data[] = [
                    'title' => 'Articles',
                    'url' => Url::to(['content/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'menu') {
                    $optionsClass[] = 'text-primary fw-bold';
                }

                $data[] = [
                    'title' => 'Menu',
                    'url' => Url::to(['menu/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }

            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_PARAMETER.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'parameter') {
                    $optionsClass[] = 'text-primary fw-bold';
                }

                $data[] = [
                    'title' => 'Paramètres',
                    'url' => Url::to(['parameter/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }
            return $data;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
