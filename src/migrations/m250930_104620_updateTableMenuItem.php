<?php
/**
 * m250930_104620_updateTableMenuItem.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;

use yii\db\Migration;

class m250930_104620_updateTableMenuItem extends Migration
{
    public function up()
    {

        $this->addColumn('{{%menuItems}}', 'route', $this->string()->defaultValue(null)->after('contentId'));
        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%menuItems}}', 'route');
        return true;
    }
}
