<?php
/**
 * Cms.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\helpers;

use Exception;
use fractalCms\helpers\ConfigType as ConfigTypeHelpers;
use fractalCms\models\ConfigItem;
use fractalCms\models\Content;
use fractalCms\models\MenuItem;
use fractalCms\models\Parameter;
use Yii;
use yii\db\ActiveQuery;
use yii\di\Container;
use yii\web\Controller;

class Cms
{

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
