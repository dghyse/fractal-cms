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

class m250830_084517_updateCmsDb extends Migration
{
    public function up()
    {
        $this->addColumn('{{items}}', 'active', $this->boolean()->defaultValue(false)->after('configItemId'));
        $this->alterColumn('{{items}}', 'data', $this->json()->defaultValue(null));

        return true;
    }

    public function down()
    {
        $this->dropColumn('{{items}}', 'active');
        return true;
    }
}
