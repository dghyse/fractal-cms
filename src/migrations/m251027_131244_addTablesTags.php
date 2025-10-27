<?php
/**
 * m251027_131244_addTablesTags.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;
use yii\db\Migration;

class m251027_131244_addTablesTags extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%tags}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'name'=> $this->string(255)->null()->defaultValue(null)->unique(),
                'slugId' => $this->bigInteger(),
                'seoId' => $this->bigInteger(),
                'configTypeId' => $this->bigInteger(),
                'active'=> $this->boolean()->defaultValue(false),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );

        $this->addForeignKey(
            'tags_configTypeId_fk',
            '{{%tags}}',
            'configTypeId',
            '{{%configTypes}}',
            'id',
            'NO ACTION',
            'NO ACTION');

        $this->addForeignKey(
            'tags_seoId_fk',
            '{{%tags}}',
            'seoId',
            '{{%seos}}',
            'id',
            'NO ACTION',
            'NO ACTION');

        $this->addForeignKey(
            'tags_slugId_fk',
            '{{%tags}}',
            'slugId',
            '{{%slugs}}',
            'id',
            'NO ACTION',
            'NO ACTION');

        $this->createTable(
            '{{%contentTags}}',
            [
                'contentId'=> $this->bigInteger(),
                'tagId' => $this->bigInteger(),
                'order' => $this->integer()->defaultValue(1),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );
        $this->addPrimaryKey('contentTags_pk','{{%contentTags}}',['contentId','tagId']);
        $this->createIndex('contentTags_order_idx','{{%contentTags}}','order');

        $this->addForeignKey(
            'contentTags_contents_fk',
            '{{%contentTags}}',
            'contentId',
            '{{%contents}}',
            'id',
            'NO ACTION',
            'NO ACTION');
        $this->addForeignKey(
            'contentTags_tags_fk',
            '{{%contentTags}}',
            'tagId',
            '{{%tags}}',
            'id',
            'NO ACTION',
            'NO ACTION');


        $this->createTable(
            '{{%tagItems}}',
            [
                'tagId'=> $this->bigInteger(),
                'itemId' => $this->bigInteger(),
                'order' => $this->integer()->defaultValue(1),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );
        $this->addPrimaryKey('tagItems_pk','{{%tagItems}}',['tagId','itemId']);
        $this->createIndex('tagItems_order_idx','{{%tagItems}}','order');

        $this->addForeignKey(
            'tagItems_tags_fk',
            '{{%tagItems}}',
            'tagId',
            '{{%tags}}',
            'id',
            'NO ACTION',
            'NO ACTION');
        $this->addForeignKey(
            'tagItems_items_fk',
            '{{%tagItems}}',
            'itemId',
            '{{%items}}',
            'id',
            'NO ACTION',
            'NO ACTION');


        return true;
    }

    public function down()
    {
        $this->dropForeignKey('tagItems_items_fk', '{{%tagItems}}');
        $this->dropForeignKey('tagItems_tags_fk', '{{%tagItems}}');
        $this->dropIndex('tagItems_order_idx', '{{%tagItems}}');
        $this->dropPrimaryKey('tagItems_pk', '{{%tagItems}}');
        $this->dropTable('{{%tagItems}}');



        $this->dropForeignKey('contentTags_tags_fk', '{{%contentTags}}');
        $this->dropForeignKey('contentTags_contents_fk', '{{%contentTags}}');
        $this->dropIndex('contentTags_order_idx','{{%contentTags}}');
        $this->dropPrimaryKey('contentTags_pk','{{%contentTags}}');
        $this->dropTable('{{%contentTags}}');


        $this->dropForeignKey('tags_slugId_fk', '{{%tags}}');
        $this->dropForeignKey('tags_seoId_fk', '{{%tags}}');
        $this->dropTable('{{%tags}}');
        return true;
    }
}
