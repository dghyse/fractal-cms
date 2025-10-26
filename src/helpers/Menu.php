<?php
/**
 * Menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms/helpers
 */
namespace fractalCms\helpers;

use fractalCms\components\Constant;
use yii\base\Component;
use Exception;
use Yii;
use yii\helpers\Url;

class Menu extends Component
{

    /**
     * Get Cms menu
     *
     * @return array
     * @throws Exception
     */
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
     * Build Cms menu
     *
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
            $configuration = [
                'title' => 'Configuration',
                'url' => null,
                'optionsClass' => [],
                'children' => []
            ];
            $contents = [
                'title' => 'Contenus',
                'url' => null,
                'optionsClass' => [],
                'children' => []
            ];
            $admins = [
                'title' => 'Administration',
                'url' => null,
                'optionsClass' => [],
                'children' => []
            ];
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'user') {
                    $optionsClass[] = 'text-primary fw-bold';
                }
                if(empty($admins['optionsClass']) === true) {
                    $admins['optionsClass'] = $optionsClass;
                }
                $admins['children'][] =  [
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
                if(empty($configuration['optionsClass']) === true) {
                    $configuration['optionsClass'] = $optionsClass;
                }
                $configuration['children'][] = [
                    'title' => 'Configuration article',
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
                if(empty($configuration['optionsClass']) === true) {
                    $configuration['optionsClass'] = $optionsClass;
                }
                $configuration['children'][] = [
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
                if(empty($contents['optionsClass']) === true) {
                    $contents['optionsClass'] = $optionsClass;
                }

                $contents['children'][] = [
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
                if(empty($contents['optionsClass']) === true) {
                    $contents['optionsClass'] = $optionsClass;
                }
                $contents['children'][] = [
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
                if(empty($configuration['optionsClass']) === true) {
                    $configuration['optionsClass'] = $optionsClass;
                }
                $configuration['children'][] = [
                    'title' => 'Paramètres',
                    'url' => Url::to(['parameter/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }
            $data = [];
            if (empty($admins['children']) === false) {
                $data[] = $admins;
            }
            if (empty($configuration['children']) === false) {
                $data[] = $configuration;
            }
            if (empty($contents['children']) === false) {
                $data[] = $contents;
            }
            return $data;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
