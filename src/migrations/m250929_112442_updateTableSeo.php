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

class m250929_112442_updateTableSeo extends Migration
{
    public function up()
    {
        /*
         * <changefreq>monthly</changefreq>
         * <priority>0.6</priority>
         */
        $this->addColumn('{{%seos}}', 'noFollow', $this->boolean()->defaultValue(false)->after('priority'));
        $this->addColumn('{{%seos}}', 'ogMeta', $this->boolean()->defaultValue(true)->after('noFollow'));
        $this->addColumn('{{%seos}}', 'addJsonLd', $this->boolean()->defaultValue(true)->after('ogMeta'));
        $this->addColumn('{{%seos}}', 'imgPath', $this->string()->defaultValue(null)->after('addJsonLd'));


        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%seos}}', 'imgPath');
        $this->dropColumn('{{%seos}}', 'addJsonLd');
        $this->dropColumn('{{%seos}}', 'ogMeta');
        $this->dropColumn('{{%seos}}', 'noFollow');
        return true;
    }
}
