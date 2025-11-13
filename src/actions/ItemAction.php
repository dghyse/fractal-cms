<?php
/**
 * ItemAction.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\actions
 */
namespace fractalCms\actions;

use fractalCms\helpers\Cms;
use fractalCms\models\Content;
use fractalCms\models\ContentItem;
use fractalCms\models\Item;
use fractalCms\models\Tag;
use fractalCms\models\TagItem;
use yii\base\Action;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;

class ItemAction extends Action
{

    /** @var class-string<Content | Tag> */
    public $targetClass = Content::class;

    /** @var class-string<ContentItem | TagItem> */
    public $targetRelationClass = ContentItem::class;

    /**
     * Function To add, move and delete item in Content form
     *
     * @param $targetId
     * @return string
     *
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function run($targetId) : string
    {
        try {
            $target = $this->targetClass::findOne(['id' => $targetId]);
            if ($target === null) {
                throw new NotFoundHttpException('target not found');
            }
            $target->scenario = $this->targetClass::SCENARIO_UPDATE;
            $model = Yii::createObject(Item::class);
            $model->scenario = Item::SCENARIO_CREATE;
            $request = Yii::$app->request;
            if ($request->isPost === true) {
                $body = $request->getBodyParams();
                //Load current model
                $model->load($body);
                //Load content data if there are update in front
                $target->load($body);
                if (isset($body['addItem']) === true && $model->validate() === true) {
                    $model->save();
                    $model->refresh();
                    $targetItem = $target->attachItem($model);
                    $targetItem->refresh();
                } elseif (empty($body['upItem']) === false) {
                    $itemId = $body['upItem'];
                    $model->move($targetId, $itemId);
                } elseif (empty($body['downItem']) === false) {
                    $itemId = $body['downItem'];
                    $model->move($targetId, $itemId, 'down');
                } elseif (empty($body['deleteItem']) === false) {
                    $itemId = $body['deleteItem'];
                    /** @var Item $modelDb */
                    $modelDb = Item::findOne($itemId);
                    if ($modelDb !== null) {
                        $target->deleteItem($modelDb);
                        $target->reOrderItems();
                    }
                }
                //Save current updated
                $target->manageItems();
            }
            $itemsQuery = $target->getItems();

            return Yii::$app->controller->renderPartial('@fractalCms/views/content/_items', [
                'configItems' => Cms::getConfigItems(),
                'itemsQuery' => $itemsQuery,
                'target' => $target
            ]);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
