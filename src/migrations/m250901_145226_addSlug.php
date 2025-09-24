<?php
/**
 * m250901_145226_addSlug.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;

use yii\db\Migration;

class m250901_145226_addSlug extends Migration
{

    public function up()
    {
        $this->createTable(
            '{{%slugs}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'host' => $this->string()->defaultValue(null),
                'path' => $this->string()->defaultValue(null)->unique(),
                'active' => $this->boolean()->defaultValue(true),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );

        $this->addForeignKey(
            'contents_slugId_fk',
            '{{%contents}}',
            'slugId',
            '{{%slugs}}',
            'id',
            'CASCADE',
            'CASCADE');

        return true;
    }

    public function down()
    {
        $this->dropForeignKey('contents_slugId_fk',
            '{{%contents}}');
        $this->dropTable('{{%slugs}}');
        return true;
    }
}
