<?php
/**
 * m250928_145624_updateTableSeo.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\migrations;


use yii\db\Migration;

class m250928_145624_updateTableSeo extends Migration
{
    public function up()
    {
        /*
         * <changefreq>monthly</changefreq>
         * <priority>0.6</priority>
         */
        $this->addColumn('{{%seos}}', 'changefreq', $this->string(15)->defaultValue('monthly')->after('description'));
        $this->addColumn('{{%seos}}', 'priority', $this->float(1)->defaultValue(0.5)->after('changefreq'));


        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%seos}}', 'priority');
        $this->dropColumn('{{%seos}}', 'changefreq');
        return true;
    }
}
