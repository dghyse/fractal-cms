<?php
/**
 * Parameter.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\models
 */
namespace fractalCms\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "parameters".
 *
 * @property int $id
 * @property string|null $group
 * @property string|null $name
 * @property string|null $value
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 */
class Parameter extends \yii\db\ActiveRecord
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
        return 'parameters';
    }

    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'group', 'name', 'dateCreate', 'dateUpdate', 'value'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'group', 'name', 'dateCreate', 'dateUpdate', 'value'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group', 'name', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['group', 'name', 'value'], 'string', 'max' => 255],
            [['group', 'name', 'value'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['group', 'name'], 'unique', 'targetAttribute' => ['group', 'name'], 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group' => 'Group',
            'name' => 'Name',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

}
