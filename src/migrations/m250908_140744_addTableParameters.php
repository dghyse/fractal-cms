<?php
/**
 * m250908_140744_addTableParameters.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;

use yii\db\Migration;

class m250908_140744_addTableParameters extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%parameters}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'group' => $this->string()->defaultValue(null),
                'name' => $this->string()->defaultValue(null),
                'value' => $this->string()->defaultValue(null),
                'dateCreate'=> $this->datetime()->null()->defaultValue(null),
                'dateUpdate'=> $this->datetime()->null()->defaultValue(null),
            ]
        );
        $this->createIndex('parameters_group_name_idx', '{{%parameters}}', ['group', 'name'], true);

        return true;
    }

    public function down()
    {
        $this->dropIndex('parameters_group_name_idx',
            '{{%parameters}}');
        $this->dropTable('{{%parameters}}');
        return true;
    }
}
