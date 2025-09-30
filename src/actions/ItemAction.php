<?php
/**
 * ItemAction.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\actions
 */
namespace fractalCms\actions;

use fractalCms\helpers\Cms;
use fractalCms\models\ConfigType;
use fractalCms\models\Content;
use fractalCms\models\ContentItem;
use fractalCms\models\Item;
use yii\base\Action;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;

class ItemAction extends Action
{

    public function run($contentId)
    {
        try {
            $content = Content::findOne(['id' => $contentId]);
            if ($content === null) {
                throw new NotFoundHttpException('content not found');
            }
            $content->scenario = Content::SCENARIO_UPDATE;
            $model = Yii::createObject(Item::class);
            $model->scenario = Item::SCENARIO_CREATE;
            $itemsCount = $content->getItems()->count();

            $request = Yii::$app->request;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                $model->load($body);
                if (isset($body['addItem']) === true) {
                    if ($model->validate() === true) {
                        $model->save();
                        $model->refresh();
                        $contentItem = Yii::createObject(ContentItem::class);
                        $contentItem->contentId = $contentId;
                        $contentItem->itemId = $model->id;
                        $contentItem->order = $itemsCount;
                        $contentItem->save();
                        $contentItem->refresh();
                    }
                } if (isset($body['upItem']) === true) {
                    $itemId = $body['upItem'];
                    if (empty($itemId)  === false) {
                        $model->move($contentId, $itemId);

                    }
                } if (isset($body['downItem']) === true) {
                    $itemId = $body['downItem'];
                    if (empty($itemId)  === false) {
                        $model->move($contentId, $itemId, 'down');
                    }
                } elseif (isset($body['deleteItem']) === true) {
                    $itemId = $body['deleteItem'];
                    /** @var Item $modelDb */
                    $modelDb = Item::findOne($itemId);
                    if ($modelDb !== null) {
                        $result = ContentItem::deleteAll(['itemId' => $modelDb->id, 'contentId' => $contentId]);
                        if ($result > 0 ) {
                            $modelDb->deleteFilesDir();
                            $modelDb->delete();
                            $content->reOrderItems();
                        }
                    }
                }
            }
            $itemsQuery = $content->getItems();

            return Yii::$app->controller->renderPartial('@fractalCms/views/content/_items', [
                'configItems' => Cms::getConfigItems(),
                'itemsQuery' => $itemsQuery,
                'content' => $content
            ]);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
