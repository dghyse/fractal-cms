<?php
// init autoloaders
require dirname(__DIR__).'../../vendor/autoload.php';

//require dirname(__DIR__).'/common/config/bootstrap.php';

require dirname(__DIR__).'../../vendor/yiisoft/yii2/Yii.php';

$config = require dirname(__DIR__).'/config/common.php';

Yii::$app = new yii\web\Application($config);