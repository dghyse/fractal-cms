<?php
/**
 * ContentItem.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\models
 */
namespace fractalCms\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "contentItems".
 *
 * @property int $contentId
 * @property int $itemId
 * @property int|null $order
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Content $content
 * @property Item $item
 */
class ContentItem extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';


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
        return 'contentItems';
    }


    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'contentId', 'itemId', 'dateCreate', 'dateUpdate', 'order',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'contentId', 'itemId', 'dateCreate', 'dateUpdate', 'order',
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['order'], 'default', 'value' => 1],
            [['contentId', 'itemId'], 'required'],
            [['contentId', 'itemId', 'order'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['contentId', 'itemId'], 'unique', 'targetAttribute' => ['contentId', 'itemId']],
            [['contentId'], 'exist', 'skipOnError' => true, 'targetClass' => Content::class, 'targetAttribute' => ['contentId' => 'id']],
            [['itemId'], 'exist', 'skipOnError' => true, 'targetClass' => Item::class, 'targetAttribute' => ['itemId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contentId' => 'Content ID',
            'itemId' => 'Item ID',
            'order' => 'Order',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    /**
     * Gets query for [[Content]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(Content::class, ['id' => 'contentId']);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::class, ['id' => 'itemId']);
    }

}
