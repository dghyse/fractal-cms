<?php
/**
 * ConfigItem.php
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
use yii\helpers\Json;

/**
 * This is the model class for table "configItems".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $config
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Item[] $items
 */
class ConfigItem extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $configArray = [];

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
        return 'configItems';
    }

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
            [['config', 'dateCreate', 'dateUpdate'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE], 'message' => 'Le nom est obligatoire'],
            [['config'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE], 'message' => 'Veuillez créer un template'],
        ];
    }

    public function afterFind()
    {
        if (is_string($this->config) === true) {
            $this->configArray = Json::decode($this->config);
        }
        parent::afterFind();
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
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::class, ['configItemId' => 'id']);
    }

}
