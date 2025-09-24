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
    public $relativeImgDirName = 'items';
    public $cacheImgPath = 'cache';
    public $version = '1.0.0';
    public $name = 'FractalCMS';


    public $commandNameSpace = 'fractalCms:';

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
                    'returnUrlParam' => '__cmsReturnUrl',
                    'identityCookie' => [
                        'name' => '_cmsIdentity', 'httpOnly' => true
                    ]
                ],
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
                            'routePrefix' => Module::getInstance()->id,
                            'rules' => [
                                [
                                    'pattern' => 'cms/tableau-de-bord',
                                    'route' => 'default/index',
                                ],
                                [
                                    'pattern' => 'cms/gestion-des-utilisateurs',
                                    'route' => 'user/index',
                                ],
                                [
                                    'pattern' => 'cms/connexion',
                                    'route' => 'authentification/login',
                                ],
                                [
                                    'pattern' => 'cms/deconnexion',
                                    'route' => 'authentification/logout',
                                ],
                                [
                                    'pattern' => 'cms/utilisateurs/<id:([^/]+)>/editer',
                                    'route' => 'user/update',
                                ],
                                [
                                    'pattern' => 'cms/utilisateurs/<id:([^/]+)>/supprimer',
                                    'route' => 'user-api/delete',
                                ],
                                [
                                    'pattern' => 'cms/utilisateurs/<id:([^/]+)>/activer-desactiver',
                                    'route' => 'user-api/activate',
                                ],
                                [
                                    'pattern' => 'cms/utilisateurs/creer',
                                    'route' => 'user/create',
                                ],
                                [
                                    'pattern' => 'cms/utilisateurs/liste',
                                    'route' => 'user/index',
                                ],
                                [
                                    'pattern' => 'cms/contents/<contentId:([^/]+)>/manage-items',
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
}
