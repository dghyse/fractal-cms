<?php
/**
 * TagItem.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\models
 */
namespace fractalCms\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tagItems".
 *
 * @property int $tagId
 * @property int $itemId
 * @property int|null $order
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Item $item
 * @property Tag $tag
 */
class TagItem extends \yii\db\ActiveRecord
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
        return 'tagItems';
    }

    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'tagId', 'itemId', 'dateCreate', 'dateUpdate', 'order',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'tagId', 'itemId', 'dateCreate', 'dateUpdate', 'order',
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
            [['tagId', 'itemId'], 'required'],
            [['tagId', 'itemId', 'order'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['tagId', 'itemId'], 'unique', 'targetAttribute' => ['tagId', 'itemId']],
            [['itemId'], 'exist', 'skipOnError' => true, 'targetClass' => Item::class, 'targetAttribute' => ['itemId' => 'id']],
            [['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => ['tagId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tagId' => 'Tag ID',
            'itemId' => 'Item ID',
            'order' => 'Order',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
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

    /**
     * Gets query for [[Tag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::class, ['id' => 'tagId']);
    }

}
