<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @version XXX
 * @package webapp\views\layouts
 *
 * @var $this yii\web\View
 * @var $config array
 * @var $model \fractalCms\models\Item
 * @var $content \fractalCms\models\Content
 * @var integer $index
 * @var integer $total
 * @var array $configItems
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
use fractalCms\helpers\Cms;
?>
<?php echo Html::beginTag('div', [
    'class' => 'row mt-1',
    'cms-item' => 'id.bind:'.$model->id.';content-id.bind:'.$content->id,
]);?>
    <div class="card">
        <div class="card-header row align-items-center">
            <div class="col-sm-8">
                <h3>
                    <?php echo ucfirst($model->configItem->name);?>
                </h3>
            </div>
            <div class="col-sm-4">
                <div class="row align-items-center">
                    <div class="col  justify-content-end">
                        <?php
                        echo Html::beginTag('button',
                            [
                                'type' => 'button',
                                'class' => 'btn btn-primary  p-0 actionButtons',
                                'name' => 'upItem',
                                'value' => $model->id,
                                'title' => 'Monter',
                                'disabled' => ($index === 0)
                            ]);
                        ?>
                        <svg width="24px"  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 11L12 8M12 8L9 11M12 8V16M7.2 20H16.8C17.9201 20 18.4802 20 18.908 19.782C19.2843 19.5903 19.5903 19.2843 19.782 18.908C20 18.4802 20 17.9201 20 16.8V7.2C20 6.0799 20 5.51984 19.782 5.09202C19.5903 4.71569 19.2843 4.40973 18.908 4.21799C18.4802 4 17.9201 4 16.8 4H7.2C6.0799 4 5.51984 4 5.09202 4.21799C4.71569 4.40973 4.40973 4.71569 4.21799 5.09202C4 5.51984 4 6.07989 4 7.2V16.8C4 17.9201 4 18.4802 4.21799 18.908C4.40973 19.2843 4.71569 19.5903 5.09202 19.782C5.51984 20 6.07989 20 7.2 20Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php
                        echo Html::endTag('button');
                        ?>
                    </div>
                    <div class="col  justify-content-end">
                        <?php
                        echo Html::beginTag('button',
                            [
                                'type' => 'button',
                                'class' => 'btn btn-primary  p-0 actionButtons',
                                'name' => 'downItem',
                                'value' =>  $model->id,
                                'title' => 'Descendre',
                                'disabled' => ($index === ($total - 1))
                            ]);
                        ?>
                        <svg width="24px"  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 13L12 16M12 16L15 13M12 16V8M7.2 20H16.8C17.9201 20 18.4802 20 18.908 19.782C19.2843 19.5903 19.5903 19.2843 19.782 18.908C20 18.4802 20 17.9201 20 16.8V7.2C20 6.0799 20 5.51984 19.782 5.09202C19.5903 4.71569 19.2843 4.40973 18.908 4.21799C18.4802 4 17.9201 4 16.8 4H7.2C6.0799 4 5.51984 4 5.09202 4.21799C4.71569 4.40973 4.40973 4.71569 4.21799 5.09202C4 5.51984 4 6.07989 4 7.2V16.8C4 17.9201 4 18.4802 4.21799 18.908C4.40973 19.2843 4.71569 19.5903 5.09202 19.782C5.51984 20 6.07989 20 7.2 20Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php
                        echo Html::endTag('button');
                        ?>
                    </div>
                    <div class="col  justify-content-end ">
                        <?php
                        echo Html::beginTag('button',
                            [
                                'type' => 'button',
                                'class' => 'btn btn-danger  p-0 actionButtons',
                                'name' => 'deleteItem',
                                'value' => $model->id,
                                'title' => 'Supprimer'
                            ]);
                        ?>
                            <svg width="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        <?php
                            echo Html::endTag('button');
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-body">
    <?php
            foreach ($model->configItem->configArray as $attribute => $data):?>
                <div class="col form-group p-0 mt-1">
                    <?php
                    $title = ($data['title']) ?? '';
                    $description = ($data['description']) ?? '';
                    $options = ($data['options']) ?? null;
                    $accept = ($data['accept']) ?? null;
                    switch ($data['type']) {
                        case Html::CONFIG_TYPE_STRING:
                            echo Html::activeLabel($content, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                            echo Html::activeTextInput($content, 'items['.$model->id.']['.$attribute.']', [
                                    'placeholder' => $title, 'class' => 'form-control',
                                'value' => $model->$attribute]);
                            break;
                        case Html::CONFIG_TYPE_FILE:
                        case Html::CONFIG_TYPE_FILES:
                            echo Html::tag('cms-file-upload', '', [
                                'title.bind' => '\''.$title.'\'',
                                'name' => Html::getInputName($content, 'items['.$model->id.']['.$attribute.']'),
                                'value' => $model->$attribute,
                                'upload-file-text' => 'Ajouter une fichier',
                                'file-type' => $accept
                            ]);
                            break;
                        case Html::CONFIG_TYPE_TEXT:
                            echo Html::activeLabel($content, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                            echo Html::activeTextarea($content, 'items['.$model->id.']['.$attribute.']',
                                ['placeholder' => $title, 'class' => 'form-control',
                                    'value' => $model->$attribute]);
                            break;
                        case Html::CONFIG_TYPE_WYSIWYG:
                            echo Html::activeLabel($content, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                            echo Html::activeHiddenInput($content, 'items['.$model->id.']['.$attribute.']', ['value' => $model->$attribute, 'class' => 'wysiwygInput']);
                            $inputNameId = Html::getInputId($content, 'items['.$model->id.']['.$attribute.']');
                            echo Html::tag('div', '',
                                [
                                    'cms-wysiwyg-editor' => 'input-id.bind:\''.$inputNameId.'\'',
                                ]);
                            break;
                        case Html::CONFIG_TYPE_CHECKBOX:
                            $values = ($data['values']) ?? null;
                            if (empty($values) === false) {
                                $values = \yii\helpers\ArrayHelper::map($values, 'value', 'name');
                                echo Html::activeCheckboxList($content, 'items['.$model->id.']['.$attribute.']', $values, ['value' => $model->$attribute]);
                            }
                            break;
                        case Html::CONFIG_TYPE_RADIO:
                            $values = ($data['values']) ?? null;
                            if (empty($values) === false) {
                                $values = \yii\helpers\ArrayHelper::map($values, 'value', 'name');
                                echo Html::activeRadioList($content, 'items['.$model->id.']['.$attribute.']', $values, ['value' => $model->$attribute]);
                            }
                            break;
                        case Html::CONFIG_TYPE_LIST_CMS:
                            $contents = Cms::getStructure(true, 'Cms');
                            $routesIntern = Cms::getInternCmsRoutes();
                            $contents = ArrayHelper::merge($contents, $routesIntern);
                            echo Html::activeLabel($content, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                            echo Html::activeDropDownList($content, 'items['.$model->id.']['.$attribute.']', ArrayHelper::map($contents, 'route', 'name', 'group'), [
                                'prompt' => 'Sélectionner une cible',
                                'value' => $model->$attribute,
                                'class' => 'form-control',
                            ]);
                            break;
                        case Html::CONFIG_TYPE_FORMS:
                                $forms = Cms::getForms();
                                echo Html::activeLabel($content, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                                echo Html::activeDropDownList($content, 'items['.$model->id.']['.$attribute.']', ArrayHelper::map($forms, 'id', 'name'), [
                                    'prompt' => 'Sélectionner un formulaire',
                                    'value' => $model->$attribute,
                                    'class' => 'form-control',
                                ]);
                            break;
                    }
                    ?>
                </div>

                <?php if (empty($description) === false):?>
                <div class="col p-0">
                    <p class="fw-lighter fst-italic">
                        <?php echo $description;?>
                    </p>
                </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
    </div>
<?php
echo Html::endTag('div');
