<?php
/**
 * m251009_154758_updateTableContent.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;

use yii\db\Migration;

class m251009_154758_updateTableContent extends Migration
{
    public function up()
    {

        $this->dropForeignKey('contents_configTypeId_fk', '{{%contents}}');
        $this->dropForeignKey('contents_seoId_fk', '{{%contents}}');
        $this->dropForeignKey('contents_slugId_fk', '{{%contents}}');

        $this->addForeignKey(
            'contents_configTypeId_fk',
            '{{%contents}}',
            'configTypeId',
            '{{%configTypes}}',
            'id',
        'NO ACTION',
        'NO ACTION'
        );

        $this->addForeignKey(
            'contents_seoId_fk',
            '{{%contents}}',
            'seoId',
            '{{%seos}}',
            'id',
            'NO ACTION',
            'NO ACTION');

        $this->addForeignKey(
            'contents_slugId_fk',
            '{{%contents}}',
            'slugId',
            '{{%slugs}}',
            'id',
            'NO ACTION',
            'NO ACTION');

        return true;
    }

    public function down()
    {
        return true;
    }
}
