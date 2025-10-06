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
 * @var \yii\web\View $this
 * @var \fractalCms\models\User $model
 */

use fractalCms\helpers\Html;
use fractalCms\components\Constant;
?>
<div class="row">
    <div class="col-sm-12">
        <?php echo Html::beginForm(); ?>

        <div class="row  justify-content-center">
            <div class="col form-check p-0">
                <?php
                echo Html::activeCheckbox($model, 'active', ['label' =>  null, 'class' => 'form-check-input']);
                echo Html::activeLabel($model, 'active', ['label' => 'Actif', 'class' => 'form-check-label']);
                ?>
            </div>
        </div>

        <div class="row  justify-content-center">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'email', ['label' => 'Identifiant (Email)', 'class' => 'form-label']);
                echo Html::activeTextInput($model, 'email', ['placeholder' => 'Email', 'class' => 'form-control']);
                ?>
            </div>
        </div>
        <div class="row  justify-content-center">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'lastname', ['label' => 'Nom', 'class' => 'form-label']);
                echo Html::activeTextInput($model, 'lastname', ['placeholder' => 'Nom', 'class' => 'form-control']);
                ?>
            </div>
        </div>
        <div class="row  justify-content-center">
            <div class="col form-group p-0">
                <?php
                echo Html::activeLabel($model, 'firstname', ['label' => 'Prénom', 'class' => 'form-label']);
                echo Html::activeTextInput($model, 'firstname', ['placeholder' => 'Prénom', 'class' => 'form-control']);
                ?>
            </div>
        </div>
        <div class="row justify-content-center mt-2">
            <div class="card col">
                <div class="card-header">
                    Mot de passe
                </div>
                <div class="card-body">
                    <div class="row  justify-content-center">
                        <div class="col form-group">
                            <?php
                            echo Html::activeLabel($model, 'tmpPassword', ['label' => 'Mot de passe', 'class' => 'form-label']);
                            echo Html::activePasswordInput($model, 'tmpPassword', ['placeholder' => 'Votre mot de passe', 'class' => 'form-control', 'autocomplete' => 'off']);
                            ?>
                        </div>
                    </div>
                    <div class="row  justify-content-center">
                        <div class="colform-group">
                            <?php
                            echo Html::activeLabel($model, 'tmpCheckPassword', ['label' => 'Mot de passe (vérification)', 'class' => 'form-label']);
                            echo Html::activePasswordInput($model, 'tmpCheckPassword', ['placeholder' => 'Vérification du mot de passe', 'class' => 'form-control', 'autocomplete' => 'off']);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                                R&egrave;gles
                            </div>
                            <div class="card-body row justify-content-center">
                                <p class=" fs-6 fst-italic m-0 p-0">8 à 16 caractères</p>
                                <p class=" fs-6 fst-italic m-0 p-0">1 ou + chiffres</p>
                                <p class=" fs-6 fst-italic m-0 p-0">1 ou + majuscules</p>
                                <p class=" fs-6 fst-italic m-0 p-0">1 ou + caractères spéciaux parmi "!@#$%"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="card col">
                <div class="card-header">
                    Droits et autorisations
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                    <?php
                        foreach ($model->authRules as $nameMain => $item):
                    ?>
                                    <?php
                                    echo Html::beginTag('div', ['class' => 'card col-sm-6 p-0', 'cms-check-rules' => '']);
                                        echo Html::beginTag('div', ['class' => 'card-header']);
                                    ?>
                                    <div class="form-check form-switch form-check-reverse">
                                        <?php
                                        echo Html::activeCheckbox($model, 'authRules['.$item['id'].'][value]',
                                            [
                                                'label' =>  null,
                                                'class' => 'form-check-input main-check',
                                                'checked' =>(boolean) $item['value'],
                                                'disabled' => Yii::$app->user->getIdentity()->id == $model->id && Yii::$app->user->can(Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_UPDATE)
                                            ]);
                                        echo Html::activeLabel($model, 'authRules['.$item['id'].'][value]', ['label' => $item['title'], 'class' => 'form-check-label']);
                                        ?>
                                    </div>
                                    <?php
                                    echo Html::endTag('div');
                                    ?>
                                    <div class="card-body">
                                        <?php
                                        if (empty($item['children']) === false):
                                        foreach ($item['children'] as $nameChild => $child):
                                            ?>
                                            <div class="form-check form-switch">
                                                <?php
                                                echo Html::activeCheckbox($model, 'authRules['.$item['id'].'][children]['.$child['id'].'][value]',
                                                    [
                                                        'label' =>  null,
                                                        'class' => 'form-check-input sub-check',
                                                        'checked' =>(boolean)$child['value'],
                                                        'disabled' => Yii::$app->user->getIdentity()->id == $model->id  && Yii::$app->user->can(Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_UPDATE)
                                                    ]);
                                                echo Html::activeLabel($model, 'authRules['.$item['id'].'][children]['.$child['id'].'][value]', ['label' => $child['title'], 'class' => 'form-check-label']);
                                                ?>
                                            </div>

                                        <?php
                                        endforeach;
                                        endif;
                                        ?>
                                    </div>
                            <?php
                            echo Html::endTag('div');
                            ?>
                    <?php
                        endforeach;
                    ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="row  justify-content-center mt-3">
            <div  class="col-sm-6 text-center form-group">
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
        </div>
        <?php  echo Html::endForm(); ?>
    </div>
</div>
