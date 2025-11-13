<?php
/**
 * index.php
 *
 * PHP Version 8.2+
 *
 * @author Philippe Gaultier <pgaultier@redcat.fr>
 * @copyright 2010-2023 Redcat
 * @license https://www.redcat.io/license license
 * @version XXX
 * @link https://www.redcat.io
 * @package www
 */


// init autoloaders
require dirname(__DIR__).'../../vendor/autoload.php';

//require dirname(__DIR__).'/common/config/bootstrap.php';

require dirname(__DIR__).'../../vendor/yiisoft/yii2/Yii.php';

$config = require dirname(__DIR__).'/config/common.php';

Yii::$app = new yii\web\Application($config);
