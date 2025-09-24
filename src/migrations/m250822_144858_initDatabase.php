<?php
/**
 * m250822_144858_initDatabase.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;

use yii\db\Migration;

class m250822_144858_initDatabase extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%users}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'email'=> $this->string(255)->null()->defaultValue(null)->unique(),
                'password'=> $this->string(64)->null()->defaultValue(null),
                'lastname'=> $this->string(255)->null()->defaultValue(null),
                'firstname'=> $this->string(255)->null()->defaultValue(null),
                'authKey'=> $this->string(255)->null()->defaultValue(null)->unique(),
                'token'=> $this->string(255)->null()->defaultValue(null)->unique(),
                'active'=> $this->boolean()->defaultValue(false),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );
        $this->createIndex('users_email_index','{{%users}}',['email'],true);

        $this->createTable(
            '{{%contents}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'name'=> $this->string(255)->null()->defaultValue(null)->unique(),
                'slugId' => $this->bigInteger(),
                'configTypeId' => $this->bigInteger(),
                'type' => 'ENUM(\'section\', \'article\') NOT NULL',
                'pathKey' =>  $this->string(255)->notNull(),
                'active'=> $this->boolean()->defaultValue(false),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );
        $this->createIndex('contents_type_pathKey_idx','{{%contents}}',['type', 'pathKey'], true);

        $this->createTable(
            '{{%items}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'configItemId' => $this->bigInteger(),
                'data' => $this->json(),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );

        $this->createTable(
            '{{%contentItems}}',
            [
                'contentId'=> $this->bigInteger(),
                'itemId' => $this->bigInteger(),
                'order' => $this->integer()->defaultValue(1),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );
        $this->addPrimaryKey('contentItems_pk','{{%contentItems}}',['contentId','itemId']);
        $this->createIndex('contentItems_order_idx','{{%contentItems}}','order');

        $this->addForeignKey(
            'contentItems_contents_fk',
            '{{%contentItems}}',
            'contentId',
            '{{%contents}}',
            'id',
            'CASCADE',
            'CASCADE');
        $this->addForeignKey(
            'contentItems_items_fk',
            '{{%contentItems}}',
            'itemId',
            '{{%items}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->createTable(
            '{{%configItems}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'name'=> $this->string(255)->null()->defaultValue(null)->unique(),
                'config' => $this->json(),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );

        $this->addForeignKey(
            'items_configItemId_fk',
            '{{%items}}',
            'configItemId',
            '{{%configItems}}',
            'id',
        'CASCADE',
        'CASCADE');


        $this->createTable(
            '{{%configTypes}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'name'=> $this->string(255)->null()->defaultValue(null)->unique(),
                'config' => $this->string(255)->null()->defaultValue(null)->unique(),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );

        $this->addForeignKey(
            'contents_configTypeId_fk',
            '{{%contents}}',
            'configTypeId',
            '{{%configTypes}}',
            'id',
            'CASCADE',
            'CASCADE');

        return true;
    }

    public function down()
    {

        $this->dropForeignKey('contents_configTypeId_fk', '{{%contents}}');
        $this->dropTable('{{%configTypes}}');

        $this->dropForeignKey('items_configItemId_fk', '{{%items}}');
        $this->dropTable('{{%configItems}}');

        $this->dropForeignKey('contentItems_items_fk', '{{%contentItems}}');
        $this->dropForeignKey('contentItems_contents_fk', '{{%contentItems}}');

        $this->dropIndex('contentItems_order_idx','{{%contentItems}}');
        $this->dropPrimaryKey('contentItems_pk','{{%contentItems}}');
        $this->dropTable('{{%contentItems}}');
        $this->dropTable('{{%items}}');
        $this->dropIndex('contents_type_pathKey_idx');
        $this->dropTable('{{%contents}}');


        $this->dropIndex('users_email_index','{{%users}}');
        $this->dropTable('users');

        return true;
    }
}
