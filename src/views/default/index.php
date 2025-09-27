<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 *
 * @var \yii\web\View $this
 * @var \fractalCms\models\User $model
 * @var $nbSections
 * @var $nbArticles
 * @var $lastDate;
 */

use fractalCms\Module;

$moduleInstance = Module::getInstance();
?>
<!-- Main -->
<main class="container mx-auto px-6 py-10">

    <!-- Bienvenue -->
    <section class="mb-10">
        <h1 class="text-2xl font-bold">👋 Bienvenue, <?php echo ucfirst($model->firstname);?></h1>
        <p class="text-gray-600">Gérez vos contenus et sections simplement avec <?php echo $moduleInstance->name.' '.$moduleInstance->version?></p>
    </section>

    <!-- Dashboard en 2 colonnes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Vue d’ensemble -->
        <section class="md:col-span-2 bg-white border border-gray-200 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-blue-700 mb-4">Vue d’ensemble</h2>
            <ul class="space-y-2 text-gray-700">
                <li> Sections principales : <span class="font-bold"><?php echo $nbSections;?></span></li>
                <li> Articles publiés : <span class="font-bold"><?php echo $nbArticles;?></span></li>
                <li> Dernière modification : <span class="font-bold"><?php echo Yii::$app->formatter->asDate($lastDate);?></li>
            </ul>
        </section>
    </div>

</main>
