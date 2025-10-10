<?php
/**
 * m251001_072806_updateTableSeo.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;

use yii\db\Migration;

class m251001_072806_updateTableSeo extends Migration
{
    public function up()
    {
        /*
         * <changefreq>monthly</changefreq>
         * <priority>0.6</priority>
         */
        $this->addColumn('{{%seos}}', 'twitterMeta', $this->boolean()->defaultValue(true)->after('ogMeta'));


        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%seos}}', 'twitterMeta');
        return true;
    }
}
