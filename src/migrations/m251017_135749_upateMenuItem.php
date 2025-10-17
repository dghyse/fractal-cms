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


use fractalCms\models\MenuItem;
use yii\db\Migration;

class m251017_135749_upateMenuItem extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%menuItems}}', 'pathKey', $this->string(255)->defaultValue(null));
        $this->dropIndex('pathKey','{{%menuItems}}');


        $menuItemQuery = MenuItem::find()->where(['menuItemId' => null])->orderBy(['pathKey' => SORT_ASC]);
        /** @var MenuItem $menuItem */
        foreach ($menuItemQuery->each() as $index => $menuItem) {
            $this->update('{{%menuItems}}',['order' => $index], 'id=:id', [':id' => $menuItem->id]);
        }

        $menuItemQuery = MenuItem::find()->where(['not', ['menuItemId' => null]])->orderBy(['menuItemId' => SORT_ASC]);
        $index = 0;
        $menuItemId = null;
        /** @var MenuItem $menuItem */
        foreach ($menuItemQuery->each() as  $menuItem) {
            if ($menuItemId != $menuItem->menuItemId) {
                $menuItemId = $menuItem->menuItemId;
                $index = 0;
            }
            $this->update('{{%menuItems}}',['order' => $index], 'id=:id', [':id' => $menuItem->id]);
            $index += 1;
        }

        return true;
    }

    public function down()
    {
        $this->alterColumn('{{%menuItems}}', 'pathKey', $this->string(255)->notNull()->unique());
        return true;
    }
}
