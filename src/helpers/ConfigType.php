<?php
/**
 * ConfigType.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package cms/helpers
 */
namespace fractalCms\helpers;

use fractalCms\components\Constant;
use fractalCms\controllers\CmsController;
use yii\base\Component;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use yii\helpers\Inflector;
use Yii;


class ConfigType extends Component
{

    public function getCmsRoutes($targetClass, $group = null) : array
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            return $this->getRoutes($targetClass, $group);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    /**
     * @return array
     * @throws Exception
     */
    protected function getRoutes($targetClass, string $customGroup = null) : array
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            $data = [];
            //Get controller Path
            $controllerPaths[] = [
                'moduleId' => '',
                'namespace' => Yii::$app->controllerNamespace,
                'path' => Yii::$app->controllerPath,
            ];

            foreach ($controllerPaths as $info) {
                $files = scandir($info['path']);
                foreach ($files as $file) {
                    if (preg_match('/^((.+)Controller).php$/', $file, $matches) >= 1) {
                        $controllerId = Inflector::camel2id($matches[2]);
                        $class = $info['namespace'].'\\'.$matches[1];
                        $ref = new ReflectionClass($class);
                        if ($ref !== null) {
                            $parentClass = $ref->getParentClass();
                            $canLoad = true;
                            if( $parentClass !== false && $parentClass->name !== $targetClass) {
                                $canLoad = false;
                            }
                            if ($canLoad === true) {
                                $defaultAction = $ref->getProperty('defaultAction')->getValue($ref->newInstanceWithoutConstructor());
                                $methods = $ref->getMethods(ReflectionMethod::IS_PUBLIC);
                                foreach ($methods as $method) {
                                    if (strncmp('action', $method->name, 6) === 0 && ($method->name !== 'actions')) {
                                        $realMethod = str_replace('action', '', $method->name);
                                        $actionId = Inflector::camel2id($realMethod);
                                        if ($actionId === $defaultAction) {
                                            $actionId = '';
                                        }
                                        $route = $info['moduleId'].'/'.$controllerId.'/'.$actionId;
                                        $group = $matches[0];
                                        if ($customGroup !== null) {
                                            $group = $customGroup;
                                        }
                                        $data[] = [
                                            'id' => $route,
                                            'route' => $route,
                                            'name' => $route,
                                            'group' => $group
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $data;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}

