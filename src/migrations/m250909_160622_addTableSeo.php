<?php
/**
 * m250909_160622_addTableSeo.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;

use yii\db\Migration;

class m250909_160622_addTableSeo extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%seos}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'title' => $this->string()->defaultValue(null),
                'description' => $this->text()->defaultValue(null),
                'active' => $this->boolean()->defaultValue(false),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );

        $this->addColumn('{{%contents}}', 'seoId', $this->bigInteger(20)->after('slugId'));

        $this->addForeignKey(
            'contents_seoId_fk',
            '{{%contents}}',
            'seoId',
            '{{%seos}}',
            'id',
            'CASCADE',
            'CASCADE');

        return true;
    }

    public function down()
    {
        $this->dropForeignKey('contents_seoId_fk',
            '{{%contents}}');
        $this->dropColumn('{{%contents}}', 'seoId');
        $this->dropTable('{{%seos}}');
        return true;
    }
}
