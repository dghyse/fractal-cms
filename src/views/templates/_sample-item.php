<?php
/**
 * _sample-item.php
 *
 * PHP Version 8.2+
 *
 * @version XXX
 * @package webapp\views\layouts
 *
 * @var $this yii\web\View
 * @var $model \fractalCms\models\Item
 * @var $target \fractalCms\models\Content | \fractalCms\models\Tag
 */

use fractalCms\helpers\Html;
use yii\helpers\ArrayHelper;
use fractalCms\helpers\Cms;
?>
<?php
//for each attribute
foreach ($model->configItem->configArray as $attribute => $data):?>
    <div class="col form-group p-0 mt-1">
        <?php
        $title = ($data['title']) ?? '';
        $description = ($data['description']) ?? '';
        $options = ($data['options']) ?? null;
        $accept = ($data['accept']) ?? null;
        switch ($data['type']) {
            case Html::CONFIG_TYPE_STRING:
                echo Html::activeLabel($target, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                echo Html::activeTextInput($target, 'items['.$model->id.']['.$attribute.']', [
                    'placeholder' => $title, 'class' => 'form-control',
                    'value' => $model->$attribute]);
                break;
            case Html::CONFIG_TYPE_FILE:
            case Html::CONFIG_TYPE_FILES:
                echo Html::tag('cms-file-upload', '', [
                    'title.bind' => '\''.$title.'\'',
                    'name' => Html::getInputName($target, 'items['.$model->id.']['.$attribute.']'),
                    'value' => $model->$attribute,
                    'upload-file-text' => 'Ajouter une fichier',
                    'file-type' => $accept
                ]);
                break;
            case Html::CONFIG_TYPE_TEXT:
                echo Html::activeLabel($target, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                if(is_array($options) === false) {
                    $options = [];
                }
                $options['placeholder'] = $title;
                $options['value'] = $model->$attribute;
                $class = 'form-control';
                if (isset($options['class']) === true) {
                    $options['class'] = $options['class'].' '.$class;
                }
                $options['style'] = 'width:100%;';
                echo Html::activeTextarea($target, 'items['.$model->id.']['.$attribute.']',$options);
                break;
            case Html::CONFIG_TYPE_WYSIWYG:
                echo Html::activeLabel($target, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                echo Html::activeHiddenInput($target, 'items['.$model->id.']['.$attribute.']', ['value' => $model->$attribute, 'class' => 'wysiwygInput']);
                $inputNameId = Html::getInputId($target, 'items['.$model->id.']['.$attribute.']');
                echo Html::tag('div', '',
                    [
                        'cms-wysiwyg-editor' => 'input-id.bind:\''.$inputNameId.'\'',
                    ]);
                break;
            case Html::CONFIG_TYPE_CHECKBOX:
                $values = ($data['values']) ?? null;
                if (empty($values) === false) {
                    $values = \yii\helpers\ArrayHelper::map($values, 'value', 'name');
                    echo Html::activeCheckboxList($target, 'items['.$model->id.']['.$attribute.']', $values, ['value' => $model->$attribute]);
                }
                break;
            case Html::CONFIG_TYPE_RADIO:
                $values = ($data['values']) ?? null;
                if (empty($values) === false) {
                    $values = \yii\helpers\ArrayHelper::map($values, 'value', 'name');
                    echo Html::activeRadioList($target, 'items['.$model->id.']['.$attribute.']', $values, ['value' => $model->$attribute]);
                }
                break;
            case Html::CONFIG_TYPE_LIST_CMS:
                $contents = Cms::getStructure(true, 'Cms');
                $tags = Cms::getTags(true, 'Tag');
                $routesIntern = Cms::getInternCmsRoutes();
                $contents = ArrayHelper::merge($contents, $tags, $routesIntern);
                echo Html::activeLabel($target, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                echo Html::activeDropDownList($target, 'items['.$model->id.']['.$attribute.']', ArrayHelper::map($contents, 'route', 'name', 'group'), [
                    'prompt' => 'Sélectionner une cible',
                    'value' => $model->$attribute,
                    'class' => 'form-control',
                ]);
                break;
            case Html::CONFIG_TYPE_FORMS:
                $forms = Cms::getForms();
                echo Html::activeLabel($target, 'items['.$model->id.']['.$attribute.']', ['label' => $title, 'class' => 'form-label']);
                echo Html::activeDropDownList($target, 'items['.$model->id.']['.$attribute.']', ArrayHelper::map($forms, 'id', 'name'), [
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
