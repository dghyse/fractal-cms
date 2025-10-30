<?php
/**
 * ContentController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\controllers
 */

namespace fractalCms\controllers;

use Exception;
use fractalCms\components\Constant;
use fractalCms\helpers\Cms;
use fractalCms\models\ConfigType;
use fractalCms\models\Content;
use fractalCms\models\Seo;
use fractalCms\models\Slug;
use fractalCms\models\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ContentController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'update', 'create'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => [Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_LIST],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => [Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_CREATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => [Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_UPDATE],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['default/index']);
                    }
                ]
            ]
        ];
        return $behaviors;
    }


    /**
     * Liste
     *
     * @return string
     * @throws Exception
     */
    public function actionIndex() : string
    {
        try {
            $modelQuery = Content::find()->orderBy(['pathKey' => SORT_ASC]);
            $structure = Cms::buildStructure($modelQuery);
            return $this->render('index', [
                'models' => $modelQuery->all(),
                'structure' => $structure
            ]);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Create
     *
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionCreate() : string | Response
    {
        try {
            $response = null;
            $configTypes = ConfigType::find()->orderBy(['name' => SORT_ASC])->all();
            $sections = Cms::buildSections(false, true);
            $model = Yii::createObject(Content::class);
            $model->scenario = Content::SCENARIO_CREATE;

            $slug = Yii::createObject(Slug::class);
            $slug->scenario = Slug::SCENARIO_CREATE;

            $seo = Yii::createObject(Seo::class);
            $seo->scenario = Seo::SCENARIO_CREATE;

            $request = Yii::$app->request;

            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                $slug->load($body);
                $seo->load($body);
                $slug->path = $slug->validateAndBuild(Slug::cleanPath($model->name));
                if ($model->validate() === true && $slug->validate() === true && $seo->validate() === true) {
                    $dbTransaction = Yii::$app->db->beginTransaction();
                    $slug->save();
                    $slug->refresh();
                    $model->slugId = $slug->id;
                    $seo->save();
                    $seo->refresh();
                    $model->seoId = $seo->id;

                    $model->attach();
                    if ($model->save() === true ) {
                        $dbTransaction->commit();
                        $model->refresh();
                        $response = $this->redirect(['content/update', 'id' => $model->id]);
                    } else {
                        $dbTransaction->rollBack();
                    }
                }
            }
            if ($response === null) {
                $response =  $this->render('manage', [
                    'model' => $model,
                    'slug' => $slug,
                    'seo' => $seo,
                    'configTypes' => $configTypes,
                    'sections' => $sections,
                    'itemsQuery' => $model->getItems(),
                ]);
            }
            return  $response;
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Update
     *
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id = null) : string | Response
    {
        try {
            $configTypes = ConfigType::find()->orderBy(['name' => SORT_ASC])->all();
            $sections = Cms::buildSections(false, true);
            $tagsQuery = Tag::find()->where(['active' => 1]);
            //find content
            $model = Content::findOne(['id' => $id]);
            if ($model === null) {
                throw new NotFoundHttpException('content not found');
            }
            //find slug attach to content
            $slug = Slug::findOne($model->slugId);
            if ($slug === null) {
                throw new NotFoundHttpException('Slug not found');
            }

            //find Seo attach to content
            $seo = Seo::findOne($model->seoId);
            if ($seo === null) {
                $seo = Yii::createObject(Seo::class);
                $seo->scenario = Seo::SCENARIO_CREATE;
            } else {
                $seo->scenario = Seo::SCENARIO_UPDATE;
            }
            $slug->scenario = Slug::SCENARIO_UPDATE;
            $model->scenario = Content::SCENARIO_UPDATE;
            $request = Yii::$app->request;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $oldPathKey = $model->parentPathKey;
                $model->load($body);
                $slug->load($body);
                $seo->load($body);
                $dbTransation = Yii::$app->db->beginTransaction();
                //Check url
                $slug->path = $slug->validateAndBuild($slug->path);
                $model->manageItems();
                $model->manageTags();
                if ($model->validate() === true && $slug->validate() === true && $seo->validate()) {
                    $slug->save();
                    $seo->save();
                    if ($oldPathKey !== $model->parentPathKey) {
                        $model->attach();
                    }
                    if (empty($model->seoId) === true) {
                        $model->seoId = $seo->id;
                    }

                    if ($model->save() === true) {
                        $model->refresh();
                        $dbTransation->commit();
                    } else {
                        $dbTransation->rollBack();
                    }

                }
            }
            $itemsQuery = $model->getItems();

            return $this->render('manage', [
                'model' => $model,
                'slug' => $slug,
                'seo' => $seo,
                'configTypes' => $configTypes,
                'sections' => $sections,
                'configItems' => Cms::getConfigItems(),
                'itemsQuery' => $itemsQuery,
                'tagsQuery' => $tagsQuery,
            ]);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

}
