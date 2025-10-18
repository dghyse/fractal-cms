<?php
/**
 * m251017_135749_upateMenuItem.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */

namespace fractalCms\migrations;


use fractalCms\models\Menu;
use fractalCms\models\MenuItem;
use yii\db\Migration;

class m251017_135749_upateMenuItem extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%menuItems}}', 'order', $this->decimal(2,1));
        $this->dropIndex('pathKey','{{%menuItems}}');
        $menuId = null;
        $menuItemQuery = MenuItem::find()->where(['menuItemId' => null])->orderBy(['pathKey' => SORT_ASC]);
        $index = 1;
        /** @var MenuItem $menuItem */
        foreach ($menuItemQuery->each() as $menuItem) {
            if ($menuItem->menuId != $menuId) {
                $menuId = $menuItem->menuId;
                $index = 1;
            }
            $this->update('{{%menuItems}}',['order' => $index], 'id=:id', [':id' => $menuItem->id]);
            $index += 1;
        }

        $menuItemQuery = MenuItem::find()->where(['not', ['menuItemId' => null]])->orderBy(['menuItemId' => SORT_ASC]);
        $index = 1;
        $menuItemId = null;
        /** @var MenuItem $menuItem */
        foreach ($menuItemQuery->each() as  $menuItem) {
            if ($menuItemId != $menuItem->menuItemId) {
                $menuItemId = $menuItem->menuItemId;
                $index = 1;
            }
            $this->update('{{%menuItems}}',['order' => $index], 'id=:id', [':id' => $menuItem->id]);
            $index += 1;
        }
        $this->dropColumn('{{%menuItems}}', 'pathKey');
        return true;
    }

    public function down()
    {
        $this->addColumn('{{%menuItems}}', 'pathKey', $this->string(255)->after('route'));
        $menuItemQuery = MenuItem::find()->where(['menuItemId' => null])->orderBy([ 'menuId' => SORT_ASC,'order' => SORT_ASC]);
        $index = 1;
        $menuId = null;
        /** @var MenuItem $menuItem */
        foreach ($menuItemQuery->each() as $menuItem) {
            if ($menuId != $menuItem->menuId) {
                $menuId = $menuItem->menuId;
                $index = 1;
            }
            $pathKey = $menuItem->menuId.'.'.$index;
            $this->update('{{%menuItems}}',['pathKey' => $pathKey], 'id=:id', [':id' => $menuItem->id]);
            $index += 1;
        }

        $menuItemQuery = MenuItem::find()->where(['not', ['menuItemId' => null]])->orderBy(['menuItemId' => SORT_ASC, 'order' => SORT_ASC]);
        $index = 1;
        $parentId = null;
        /** @var MenuItem $menuItem */
        foreach ($menuItemQuery->each() as  $menuItem) {
            if ($parentId != $menuItem->menuItemId) {
                $parentId = $menuItem->menuItemId;
                $index = 1;
            }
            /** @var MenuItem $parent */
            $parent = $menuItem->getParentMenuItem()->one();
            $pathKey = $parent->pathKey;
            $pathKey .= '.'.$index;
            $this->update('{{%menuItems}}',['pathKey' => $pathKey], 'id=:id', [':id' => $menuItem->id]);
            $index += 1;
        }
        $this->alterColumn('{{%menuItems}}', 'pathKey', $this->string(255)->notNull()->unique());
        return true;
    }
}
