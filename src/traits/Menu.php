<?php
/**
 * Menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\traits
 */
namespace fractalCms\traits;

use Exception;
use fractalCms\models\MenuItem;
use Yii;
use yii\web\NotFoundHttpException;

trait Menu
{
    const SCENARIO_MOVE_MENU_ITEM = 'moveMenuItem';

    public $sourceMenuItemId;
    public $sourceIndex;
    public $destMenuItemId;
    public $destIndex;


    /**
     * Move item
     *
     * @return bool
     */
    public function moveMenuItem():bool
    {
        try {
            $success = true;
            $sourceMenuItem= MenuItem::findOne($this->sourceMenuItemId);
            if ($sourceMenuItem === null) {
                throw new NotFoundHttpException('Menu item source not found');
            }
            $destMenuItem = MenuItem::findOne($this->destMenuItemId);
            if ($destMenuItem === null) {
                throw new NotFoundHttpException('Menu item dest not found');
            }

            return $success;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
        }
    }
}
