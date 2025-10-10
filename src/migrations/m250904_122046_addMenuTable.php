<?php
/**
 * m250904_122046_addMenuTable.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;

use yii\db\Migration;

class m250904_122046_addMenuTable extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%menus}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'name' => $this->string()->unique(),
                'active' => $this->boolean()->defaultValue(true),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );

        $this->createTable(
            '{{%menuItems}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'menuId'=> $this->bigInteger(20),
                'menuItemId'=> $this->bigInteger(20),
                'contentId'=> $this->bigInteger(20),
                'pathKey' =>  $this->string(255)->notNull()->unique(),
                'name' => $this->string()->notNull(),
                'order' => $this->tinyInteger(),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );

        $this->addForeignKey(
            'menuItems_menus_fk',
            '{{%menuItems}}',
            'menuId',
            '{{%menus}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->addForeignKey(
            'menuItems_menuItems_fk',
            '{{%menuItems}}',
            'menuItemId',
            '{{%menuItems}}',
            'id',
            'CASCADE',
            'CASCADE');
        $this->addForeignKey(
            'menuItems_contents_fk',
            '{{%menuItems}}',
            'contentId',
            '{{%contents}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->createIndex('menuItems_order_idx', '{{%menuItems}}', 'order');

        return true;
    }

    public function down()
    {
        $this->dropIndex('menuItems_order_idx', '{{%menuItems}}');
        $this->dropForeignKey('menuItems_contents_fk', '{{%menuItems}}');
        $this->dropForeignKey('menuItems_menuItems_fk',
            '{{%menuItems}}');
        $this->dropForeignKey('menuItems_menus_fk',
            '{{%menuItems}}');
        $this->dropTable('{{%menuItems}}');
        $this->dropTable('{{%menus}}');
        return true;
    }
}
