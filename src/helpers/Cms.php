<?php
/**
 * Cms.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\helpers
 */
namespace fractalCms\helpers;

use Exception;
use fractalCms\controllers\CmsController;
use fractalCms\helpers\ConfigType as ConfigTypeHelpers;
use fractalCms\models\ConfigItem;
use fractalCms\models\Content;
use fractalCms\models\MenuItem;
use fractalCms\models\Parameter;
use Yii;
use yii\db\ActiveQuery;
use yii\rest\Controller;
use ReflectionClass;
use ReflectionMethod;

class Cms
{

    /**
     * Build Content list
     *
     * @param $isActive
     * @param $withSubSection
     * @return array
     * @throws Exception
     */
    public static function buildSections($isActive = false, $withSubSection = false) : array
    {
        try {
            $sections = [];
            $main = Content::find()->where(['id' => 1])->one();
            $children = $main->getChildrens($isActive, $withSubSection);
            $sections[] = [
                'id' => $main->id,
                'name' => $main->name,
                'pathKey' => $main->pathKey,
            ];

            /** @var Content $child */
            foreach ($children->each() as $child) {
                $deep = $child->getDeep();
                $prefix = str_pad('', $deep, '-');
                $sections[] = [
                    'id' => $child->id,
                    'name' => $prefix.' '.$child->name,
                    'pathKey' => $child->pathKey,
                ];
            }
            return $sections;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Build structure list
     *
     * @param $isActive
     * @param $group
     * @return array
     * @throws Exception
     */
    public static function getStructure($isActive = false, $group = null) : array
    {
        try {
            $structure = [];
            $query = Content::find();
            if ($isActive === true) {
                $query->andWhere(['active' => 1]);
            }
            $query->orderBy(['pathKey' => SORT_ASC]);
            /** @var Content $content */
            foreach ($query->each() as $content) {
                $deep = $content->getDeep();
                $prefix = str_pad('', $deep, '-');
                $sufix = $content->displayType();
                $options = [
                    'id' => $content->id,
                    'name' => $prefix.' '.$content->name.' ( '.$sufix.' )',
                    'route' => $content->getRoute(),
                ];
                if ($group !== null) {
                    $options['group'] = $group;
                }
                $structure[] = $options;

            }
            return $structure;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Build structure controllers list
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getControllerStructure() : array
    {
        try {
            $structure = [];
            $controllerNameSpace = Yii::$app->controllerNamespace;
            $controllerPath = Yii::$app->controllerPath;
            if (file_exists($controllerPath) === true) {
                foreach (scandir($controllerPath) as $controllerFile) {
                    $pathFile = $controllerPath.'/'.$controllerFile;
                    if (in_array($controllerFile, ['.', '..']) === false && is_file($pathFile) === true) {
                        $pathInfo = pathinfo($pathFile);
                        $controllerName = $pathInfo['filename'];
                        $namespace = $controllerNameSpace.'\\'.$controllerName;
                        $reflexion = new ReflectionClass($namespace);
                        $parentRefl = $reflexion->getParentClass();
                        $parentInstance = $parentRefl->newInstanceWithoutConstructor();
                        if (
                            ($parentInstance !== null) &&
                            ($parentInstance instanceof CmsController) === false &&
                            ($parentInstance instanceof Controller) === false &&
                            preg_match('/^(\w+)(Controller)$/', $controllerName, $matchesController) === 1
                        ) {
                            $controllerId = static::camelToId($matchesController[1]);
                            $methods = $reflexion->getMethods();
                            /** @var  ReflectionMethod $method */
                            foreach ($methods as $method) {
                                if (preg_match('/^(action)([A-Z].+)$/', $method->name, $matches) === 1) {
                                    $actionId = static::camelToId($matches[2]);
                                    $item = [
                                        'id' => $controllerId.'/'.$actionId,
                                        'name' => $controllerId.'/'.$actionId,
                                    ];
                                    $structure[] = $item;
                                }
                            }
                        }
                    }
                }
            }
            return $structure;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Camel case to Id
     *
     * @param $camelCase
     * @param $separator
     * @return string
     * @throws Exception
     */
    public static function camelToId($camelCase, $separator = '-') : string
    {
        try {
            $pattern = '/(?<=\w)(?=[A-Z])|(?<=[a-z])(?=\d)/';
            $snakeCase = preg_replace($pattern, $separator, $camelCase);
            return strtolower($snakeCase);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * transforme space to insecable space
     *
     * @param $string
     * @param $separator
     * @return string
     * @throws Exception
     */
    public static function insertIndivisibleSpace($string, $separator = '&nbsp;') : string
    {
        try {
            $pattern = '/(\s+)/';
            $string = preg_replace($pattern, $separator, $string);
            return $string;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Build structure with content query
     *
     * @param ActiveQuery $contentsQuery
     * @return array
     * @throws Exception
     */
    public static function buildStructure(ActiveQuery $contentsQuery) : array
    {
        try {
            $structure = [];
            $sections = [];
            $articles = [];
            /** @var Content $content */
            foreach ($contentsQuery->each() as $content) {
                if ($content->type === Content::TYPE_SECTION) {
                    $sections[$content->pathKey] = $content;
                } else {
                    $articles[$content->pathKey] = $content;
                }
            }
            /**
             * @var string $pathKey
             * @var  Content $section
             */
            foreach ($sections as $pathKey => $section) {
                $newSection =  [];
                $newSection['section'] = $section;
                $newSection['children'] = [];
                $regex = '/^'.$pathKey.'\.\w$/';
                foreach ($articles as $pathKeyArticle => $article) {
                    if (preg_match($regex, $pathKeyArticle) === 1) {
                        $newSection['children'][] = $article;
                    }
                }
                $structure[] = $newSection;
            }
            return $structure;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get Aurelia Form
     *
     * @return array[]
     * @throws Exception
     */
    public static function getForms() : array
    {
        try {
            return [
                [
                    'id' => 'form-contact',
                    'name' => 'Formulaire de contact'
                ]
            ];
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get interne CMS Route
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public static function getInternCmsRoutes()
    {
        try {
            $routes = [];
            if (Yii::$container->has(ConfigTypeHelpers::class) === true) {
                $cfHelpers = Yii::$container->get(ConfigTypeHelpers::class);
                $routes = $cfHelpers->getCmsRoutes(Controller::class, 'Interne');
            }
            return $routes;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get menu item structure
     *
     * @param $menuId
     * @param $menuItemId
     * @return array
     * @throws Exception
     */
    public static function getMenuItemStructure($menuId, $menuItemId = null) : array
    {
        try {
            $structure = [];
            $query = MenuItem::find();
            $query->andWhere(['menuId' => $menuId]);
            if ($menuItemId !== null) {
                $query->andWhere(['not', ['id' => $menuItemId]])->all();
            }
            $query->orderBy(['pathKey' => SORT_ASC]);
            /** @var MenuItem $menuItem */
            foreach ($query->each() as $menuItem) {
                $deep = $menuItem->getDeep();
                $prefix = str_pad('', $deep, '-');
                $structure[] = [
                    'id' => $menuItem->id,
                    'name' => $prefix.' '.$menuItem->name,
                ];
            }
            return $structure;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get config item list
     *
     * @return array
     * @throws Exception
     */
    public static function getConfigItems() : array
    {
        try {
            $configs = [];
            $query = ConfigItem::find();
            /** @var ConfigItem $config */
            foreach ($query->each() as $config) {
                $configs[] = [
                    'id' => $config->id,
                    'name' => $config->name,
                ];
            }
            return $configs;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get parameter
     *
     * @param $group
     * @param $name
     * @return string|null
     * @throws Exception
     */
    public static function getParameter($group, $name) : string | null
    {
        try {
            $value = null;
            $parameter = Parameter::find()->andWhere(['group' => $group, 'name' => $name])->one();
            if ($parameter !== null) {
                $value = $parameter->value;
            }
            return $value;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Clean Html to view
     *
     * @param $text
     * @return string|null
     * @throws Exception
     */
    public static function cleanHtml($text) : string | null
    {
        try {
            $value = '';
            if (is_string($text) === true) {
                $value = preg_replace('/<p>[?&nbsp;|<br\/>|<br>]*<\/p>/', '', $text);
            }
            return $value;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

}
