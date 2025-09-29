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

namespace fractalCms;


use Exception;
use fractalCms\components\UrlRule;
use fractalCms\console\AdminController;
use fractalCms\console\AuthorController;
use fractalCms\console\InitController;
use fractalCms\console\RbacController;
use fractalCms\helpers\Menu;
use fractalCms\helpers\ConfigType;
use fractalCms\helpers\SitemapBuilder;
use fractalCms\models\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\web\Application as WebApplication;
use yii\web\GroupUrlRule;
use yii\web\User as WebUser;

class Module extends \yii\base\Module implements BootstrapInterface
{


    public $layoutPath = '@fractalCms/views/layouts';
    public $layout = 'main';
    public $defaultRoute = 'default/index';
    public $filePath = '@webroot/data';
    public $relativeItemImgDirName = 'items';
    public $relativeSeoImgDirName = 'seo';
    public $cacheImgPath = 'cache';
    public $version = 'v1.1.2';
    public $name = 'FractalCMS';
    public $commandNameSpace = 'fractalCms:';

    private $_contextId = 'cms';

    public function bootstrap($app)
    {
        try {
            Yii::setAlias('@fractalCms', __DIR__);
            $app->setComponents([
                'user' => [
                    'class' => WebUser::class,
                    'identityClass' => User::class,
                    'enableAutoLogin' => true,
                    'autoRenewCookie' => true,
                    'loginUrl' => [$this->uniqueId.'/authentication/login'],
                    'idParam' => '__cmsId',
                    'returnUrlParam' => '__fractalCmsReturnUrl',
                    'identityCookie' => [
                        'name' => '_fractalCmsIdentity', 'httpOnly' => true
                    ]
                ],
            ]);

            Yii::$container->setSingleton(Menu::class, [
                'class' => Menu::class,
            ]);

            Yii::$container->setSingleton(ConfigType::class, [
                'class' => ConfigType::class,
            ]);

            Yii::$container->setSingleton(SitemapBuilder::class, [
                'class' => SitemapBuilder::class,
            ]);

            if ($app instanceof ConsoleApplication) {
                //Init migration
                if (isset($app->controllerMap['migrate']) === true) {
                    //Add migrations namespace
                    if (isset($app->controllerMap['migrate']['migrationNamespaces']) === true) {
                        $app->controllerMap['migrate']['migrationNamespaces'][] = 'fractalCms\migrations';
                    } else {
                        $app->controllerMap['migrate']['migrationNamespaces'] = ['fractalCms\migrations'];
                    }
                    //Add rbac
                    if (isset($app->controllerMap['migrate']['migrationPath']) === true) {
                        $app->controllerMap['migrate']['migrationPath'][] = '@yii/rbac/migrations';
                    } else {
                        $app->controllerMap['migrate']['migrationPath'] = ['@yii/rbac/migrations'];
                    }
                }
                $app->controllerNamespace = 'fractalCms\console';
                $app->controllerMap[$this->commandNameSpace.'rbac'] = [
                    'class' => RbacController::class,
                ];
                $app->controllerMap[$this->commandNameSpace.'admin'] = [
                    'class' => AdminController::class,
                ];
                $app->controllerMap[$this->commandNameSpace.'author'] = [
                    'class' => AuthorController::class,
                ];
                $app->controllerMap[$this->commandNameSpace.'init'] = [
                    'class' => InitController::class,
                ];
            } elseif ($app instanceof WebApplication) {
                $app->getUrlManager()->addRules(
                    [
                        new GroupUrlRule([
                            'prefix' => Module::getInstance()->id,
                            'routePrefix' => Module::getInstance()->id,
                            'rules' => [
                                [
                                    'pattern' =>'tableau-de-bord',
                                    'route' => 'default/index',
                                ],
                                [
                                    'pattern' => 'gestion-des-utilisateurs',
                                    'route' => 'user/index',
                                ],
                                [
                                    'pattern' => 'connexion',
                                    'route' => 'authentification/login',
                                ],
                                [
                                    'pattern' => 'deconnexion',
                                    'route' => 'authentication/logout',
                                ],
                                [
                                    'pattern' => 'utilisateurs/<id:([^/]+)>/editer',
                                    'route' => 'user/update',
                                ],
                                [
                                    'pattern' => 'utilisateurs/<id:([^/]+)>/supprimer',
                                    'route' => 'user-api/delete',
                                ],
                                [
                                    'pattern' => 'utilisateurs/<id:([^/]+)>/activer-desactiver',
                                    'route' => 'user-api/activate',
                                ],
                                [
                                    'pattern' => 'utilisateurs/creer',
                                    'route' => 'user/create',
                                ],
                                [
                                    'pattern' => 'utilisateurs/liste',
                                    'route' => 'user/index',
                                ],
                                [
                                    'pattern' => 'configuration-des-items/liste',
                                    'route' => 'config-item/index',
                                ],
                                [
                                    'pattern' => 'configuration-des-items/creer',
                                    'route' => 'config-item/create',
                                ],
                                [
                                    'pattern' => 'configuration-des-items/<id:([^/]+)>/editer',
                                    'route' => 'config-item/update',
                                ],
                                [
                                    'pattern' => 'configuration-des-items/<id:([^/]+)>/supprimer',
                                    'route' => 'api/config-item/delete',
                                ],

                                [
                                    'pattern' => 'configuration-type-article/liste',
                                    'route' => 'config-type/index',
                                ],
                                [
                                    'pattern' => 'configuration-type-article/creer',
                                    'route' => 'config-type/create',
                                ],
                                [
                                    'pattern' => 'configuration-type-article/<id:([^/]+)>/editer',
                                    'route' => 'config-type/update',
                                ],
                                [
                                    'pattern' => 'configuration-type-article/<id:([^/]+)>/supprimer',
                                    'route' => 'api/config-type/delete',
                                ],
                                [
                                    'pattern' => 'articles/liste',
                                    'route' => 'content/index',
                                ],
                                [
                                    'pattern' => 'articles/creer',
                                    'route' => 'content/create',
                                ],
                                [
                                    'pattern' => 'articles/<id:([^/]+)>/editer',
                                    'route' => 'content/update',
                                ],
                                [
                                    'pattern' => 'articles/<id:([^/]+)>/supprimer',
                                    'route' => 'api/content/delete',
                                ],

                                [
                                    'pattern' => 'menus/liste',
                                    'route' => 'menu/index',
                                ],
                                [
                                    'pattern' => 'menu/creer',
                                    'route' => 'menu/create',
                                ],
                                [
                                    'pattern' => 'menu/<id:([^/]+)>/editer',
                                    'route' => 'menu/update',
                                ],
                                [
                                    'pattern' => 'menu/<id:([^/]+)>/supprimer',
                                    'route' => 'api/menu/delete',
                                ],
                                [
                                    'pattern' => 'parametres/liste',
                                    'route' => 'parameter/index',
                                ],
                                [
                                    'pattern' => 'parametres/creer',
                                    'route' => 'parameter/create',
                                ],
                                [
                                    'pattern' => 'parametres/<id:([^/]+)>/editer',
                                    'route' => 'parameter/update',
                                ],
                                [
                                    'pattern' => 'parametres/<id:([^/]+)>/supprimer',
                                    'route' => 'api/parameter/delete',
                                ],

                                [
                                    'pattern' => 'contents/<contentId:([^/]+)>/manage-items',
                                    'route' => 'api/item/manage-items',
                                ],
                            ]
                        ]),
                    ], true);            //adding route here

                //Add rules to create an parse cms url
                $app->urlManager->addRules([
                    [
                        'class' => UrlRule::class,
                    ]
                ], true);
                $filePath = Yii::getAlias($this->filePath);
                if(file_exists($filePath) === false) {
                    mkdir($filePath);
                }

            }
        } catch (Exception $e){
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    public function getContextId() : string
    {
        try {
            return $this->_contextId;
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
