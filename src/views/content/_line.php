<?php
/**
 * index.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\config
 *
 * @var \fractalCms\models\Content $model
 * @var int $parentDeep
 */
use fractalCms\components\Constant;
use yii\helpers\Html;
use yii\helpers\Url;
$parentDeep = (isset($parentDeep) === true) ? $parentDeep : 0;
?>
<?php
    $classes = ['row align-items-center  p-1 border mt-1'];
    if ((boolean)$model->active === true) {
        $classes[] = 'border-success';
    } elseif((boolean)$model->active === false) {
        $classes[] = 'border-danger';
    } else {
        $classes[] = 'border-primary';
    }

    echo Html::beginTag('div', ['class' => implode(' ', $classes), 'cms-list-line' => $model->id]);
    $deep = $model->getDeep();
    $className = [];
    $className[] = 'col-sm-6';
    $prefix = '';
    if ($deep !== null && $deep !== 1) {
        $prefix = str_pad($prefix, $deep, '-');
    }
    $name = $prefix.' '.ucfirst($model->name);
    echo Html::tag('div', $name, ['class' => implode(' ', $className)]);
    echo Html::tag('div', ucfirst($model->type), ['class' => 'col-sm-3']);
    echo Html::beginTag('div', ['class' => 'col-sm-3']);
    echo Html::beginTag('div', ['class' => 'row align-items-center']);
    if (Yii::$app->user->can(Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_UPDATE) === true)  {
        echo Html::beginTag('a', ['href' => Url::to(['content/update', 'id' => $model->id]), 'class' => 'icon-link col', 'title' => 'Editer']);
        ?>
        <svg width="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z" stroke="#5468ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke="#5468ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <?php
        echo Html::endTag('a');
    }
    if (Yii::$app->user->can(Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_ACTIVATION) === true)  {
        // echo Html::beginTag('a', ['href' => Url::to(['user/activate', 'id' => $model->id]), 'class' => 'icon-link col user-button-activate', 'title' => ((boolean) $model->active === true) ? 'Désactiver' : 'Activer']);
        echo Html::beginTag('span', ['class' => 'icon-link col']);
        if ((boolean)$model->active === true):
            ?>
            <svg width="25px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-labelledby="switchOffIconTitle" stroke="#198754" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" color="#198754"> <title id="switchOffIconTitle">Switch off</title> <path fill-rule="evenodd" clip-rule="evenodd" d="M7 14C5.89543 14 5 13.1046 5 12C5 10.8954 5.89543 10 7 10C8.10457 10 9 10.8954 9 12C9 13.1046 8.10457 14 7 14Z"/> <path d="M7 17C4.23858 17 2 14.7614 2 12V12C2 9.23858 4.23858 7 7 7L16 7C18.7614 7 21 9.23858 21 12V12C21 14.7614 18.7614 17 16 17L7 17Z"/> </svg>
        <?php
        else:
            ?>

            <svg width="25px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-labelledby="switchOnIconTitle" stroke="#fd7e14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" color="#fd7e14"> <title id="switchOnIconTitle">Switch on</title> <path fill-rule="evenodd" clip-rule="evenodd" d="M17 10C18.1046 10 19 10.8954 19 12C19 13.1046 18.1046 14 17 14C15.8954 14 15 13.1046 15 12C15 10.8954 15.8954 10 17 10Z"/> <path d="M17 7C19.7614 7 22 9.23858 22 12V12C22 14.7614 19.7614 17 17 17L8 17C5.23858 17 3 14.7614 3 12V12C3 9.23858 5.23858 7 8 7L17 7Z"/> </svg>
        <?php
        endif;
        echo Html::endTag('span');
        // echo Html::endTag('a');
    }
    if (Yii::$app->user->can(Constant::PERMISSION_MAIN_CONTENT.Constant::PERMISSION_ACTION_DELETE) === true && $model->pathKey !== '1')  {
        echo Html::beginTag('a', ['href' => Url::to(['api/content/delete', 'id' => $model->id]), 'class' => 'icon-link col user-button-delete', 'title' => 'Supprimer']);
        ?>
        <svg width="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <?php
        echo Html::endTag('a');
    } else {
        echo Html::tag('span', '', ['class' => 'col']);
    }

    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
?>
