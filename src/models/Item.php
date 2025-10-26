<?php
/**
 * Item.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\models
 */
namespace fractalCms\models;

use Exception;
use fractalCms\Module;
use fractalCms\traits\Elastic;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Json;

/**
 * This is the model class for table "items".
 *
 * @property int $id
 * @property int|null $configItemId
 * @property int|null $active
 * @property string|null $data
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property ConfigItem $configItem
 * @property ContentItem[] $contentItems
 * @property Content[] $contents
 */
class Item extends \yii\db\ActiveRecord
{

    use Elastic;

    /**
     * Path of custom view
     *
     * @var string
     */
    public string $viewPath;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_ADD = 'add';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'dateCreate',
            'updatedAtAttribute' => 'dateUpdate',
            'value' => new Expression('NOW()'),
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
    }

    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'configItemId', 'data', 'dateCreate', 'dateUpdate', 'active'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'configItemId', 'data', 'dateCreate', 'dateUpdate', 'active'
        ];
        $scenarios[self::SCENARIO_ADD] = [
            'configItemId', 'data', 'dateCreate', 'dateUpdate', 'active'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['configItemId', 'data', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['configItemId', 'active'], 'integer'],
            [['data', 'dateCreate', 'dateUpdate'], 'safe'],
            [['configItemId'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['configItemId'], 'exist', 'skipOnError' => true,
                'targetClass' => ConfigItem::class, 'targetAttribute' => ['configItemId' => 'id']
            ],
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $module = Module::getInstance();
        if (is_string($this->data) === true && empty($this->data) === false) {
            $this->data = Json::decode($this->data);
        }
        if ($this->elasticModel === null) {
            $this->elasticModel = Yii::createObject(ElasticModel::class, ['jsonConfig' => $this->configItem->configArray, 'config' => []]);
            $this->elasticModel->attributes = $this->data;
        }
        $viewName = strtolower(str_replace('-', '_', $this->configItem->name));
        $viewPathAlias = trim($module->viewItemPath, '/').'/'.$viewName.'.php';
        $viewPath = Yii::getAlias($viewPathAlias);
        if(file_exists($viewPath) === true) {
            $this->viewPath = $viewPathAlias;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'configItemId' => 'Config Item ID',
            'data' => 'Data',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    /**
     * Gets query for [[ConfigItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfigItem()
    {
        return $this->hasOne(ConfigItem::class, ['id' => 'configItemId']);
    }

    /**
     * Gets query for [[ContentItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContentItems()
    {
        return $this->hasMany(ContentItem::class, ['itemId' => 'id']);
    }

    /**
     * Gets query for [[Contents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Content::class, ['id' => 'contentId'])->viaTable('contentItems', ['itemId' => 'id']);
    }

    public function move($contentId, $itemId, $direction = 'up') :bool
    {
        try {
            $success = false;
            $to = ($direction === 'up') ? -1 : 1;
            $currentContentItem = ContentItem::find()
                ->andWhere(['contentId' => $contentId, 'itemId' => $itemId])->one();
            if ($currentContentItem !== null) {
                $previousContentItem = ContentItem::find()
                    ->andWhere(['contentId' => $contentId, 'order' => $currentContentItem->order + $to])->one();
                if ($previousContentItem !== null) {
                    $currentOrder = $currentContentItem->order;
                    $currentContentItem->scenario = ContentItem::SCENARIO_UPDATE;
                    $currentContentItem->order = $previousContentItem->order;
                    $success = $currentContentItem->save();
                    if ($success === true) {
                        $previousContentItem->scenario = ContentItem::SCENARIO_UPDATE;
                        $previousContentItem->order = $currentOrder;
                        $success = $previousContentItem->save();
                    }
                }
            }
            return $success;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


}
