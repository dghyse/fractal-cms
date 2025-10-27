<?php
/**
 * Item.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\traits
 */
namespace fractalCms\traits;

use fractalCms\models\Item as ItemModel;
use Exception;
use Yii;

trait Item
{
    /**
     * Manage items
     *
     * @param $deleteSource
     * @return void
     * @throws \yii\db\Exception
     */
    public function manageItems($deleteSource = true) : void
    {
        try {
            $models =  $this->items;
            if (is_array($models) === true) {
                foreach ($models as $id => $data) {
                    $dbModel = ItemModel::findOne($id);
                    if ($dbModel !== null) {
                        $dbModel->scenario = ItemModel::SCENARIO_UPDATE;
                        $newData = $dbModel->prepareData($data, $deleteSource);
                        $dbModel->elasticModel->load($newData, '');
                        $dbModel->data = $dbModel->elasticModel->toJson();
                        $dbModel->active = 1;
                        if ($dbModel->validate() === true) {
                            $dbModel->save();
                        } else {
                            $this->items[$id]['errors'] = $dbModel->errors;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

}
