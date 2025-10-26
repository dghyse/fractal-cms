<?php
/**
 * ConfigType.php
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
 * This is the model class for table "configTypes".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $config
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Content[] $contents
 */
class ConfigType extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
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
        return 'configTypes';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'name', 'config', 'dateCreate', 'dateUpdate',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'name', 'config', 'dateCreate', 'dateUpdate',
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'config', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['name', 'config'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['config'], 'unique'],
            [['name', 'config'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'config' => 'Config',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    /**
     * Gets query for [[Contents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Content::class, ['configTypeId' => 'id']);
    }

}
