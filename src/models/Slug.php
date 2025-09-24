<?php
/**
 * Slug.php
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
use Exception;

/**
 * This is the model class for table "slugs".
 *
 * @property int $id
 * @property string|null $host
 * @property string|null $path
 * @property int|null $active
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Content[] $contents
 * @property Content $content
 */
class Slug extends \yii\db\ActiveRecord
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
        return 'slugs';
    }

    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'host', 'path', 'dateCreate', 'dateUpdate','active'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'host', 'path', 'dateCreate', 'dateUpdate','active'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['host', 'path', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['active'], 'default', 'value' => 1],
            [['active'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['host', 'path'], 'string', 'max' => 255],
            [['path'], 'unique'],
            [['path'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'host' => 'Host',
            'path' => 'Path',
            'active' => 'Active',
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
        return $this->hasMany(Content::class, ['slugId' => 'id']);
    }

    public function getContent()
    {
        return $this->hasOne(Content::class, ['slugId' => 'id']);
    }

    public static function cleanPath($string) : string
    {
        try {
            //switch accents to simpler text
            $string = preg_replace("/\s+/","-", $string);
            $string = str_replace(
                ['é','è', 'ë', 'ê', 'à', 'ä', 'â', 'ù', 'ü', 'û', 'ö', 'ô', 'ï', 'ï', 'ü', 'û', 'ç'],
                ['e','e', 'e', 'e', 'a', 'a', 'a', 'u', 'u', 'u', 'o', 'o', 'i', 'i', 'u', 'u', 'c'], $string);
            return trim(strtolower($string));;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function validateAndBuild($path)
    {
        try {
             $nbSlugQuery = static::find()->andWhere(['path' => $path]);
             if ($this->isNewRecord === false) {
                 $nbSlugQuery->andWhere(['not', 'id' => $this->id])->count();
             }
             $nbSlug = $nbSlugQuery->count();
             if ($nbSlug > 0) {
                 $path = $path.'-'.str_pad($nbSlug + 1, 3, '0', STR_PAD_LEFT);
             }
             return $path;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

}
