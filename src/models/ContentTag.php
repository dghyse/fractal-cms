<?php
/**
 * ContentTag.php
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
 * This is the model class for table "contentTags".
 *
 * @property int $contentId
 * @property int $tagId
 * @property int|null $order
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Content $content
 * @property Tag $tag
 */
class ContentTag extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contentTags';
    }


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
            [['contentId', 'tagId'], 'required'],
            [['contentId', 'tagId', 'order'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['contentId', 'tagId'], 'unique', 'targetAttribute' => ['contentId', 'tagId']],
            [['contentId'], 'exist', 'skipOnError' => true, 'targetClass' => Content::class, 'targetAttribute' => ['contentId' => 'id']],
            [['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => ['tagId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contentId' => 'Content ID',
            'tagId' => 'Tag ID',
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
     * Gets query for [[Tag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::class, ['id' => 'tagId']);
    }

}
