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
    public $destMenuItemId;


    /**
     * Move menu item
     *
     * @return bool
     */
    public function moveMenuItem() : bool
    {
        try {
            $success = false;
            $sourceMenuItem = MenuItem::findOne($this->sourceMenuItemId);
            $destMenuItem = MenuItem::findOne($this->destMenuItemId);
            if ($sourceMenuItem !== null && $destMenuItem !== null) {
                $success = $destMenuItem->move($sourceMenuItem);
            }
            return $success;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
        }
    }
}
