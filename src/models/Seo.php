<?php
/**
 * Seo.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\models
 */
namespace fractalCms\models;

use fractalCms\Module;
use fractalCms\traits\Upload;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\HtmlPurifier;
use Exception;

/**
 * This is the model class for table "seos".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $changefreq
 * @property float $priority
 * @property int $noFollow
 * @property int $ogMeta
 * @property int $twitterMeta
 * @property int $addJsonLd
 * @property int $imgPath
 * @property int|null $active
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Content[] $contents
 */
class Seo extends \yii\db\ActiveRecord
{
    use Upload;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const FREQUENTLY_DAILY = 'daily';
    const FREQUENTLY_WEEKLY = 'weekly';
    const FREQUENTLY_MONTHLY = 'monthly';
    public static $accept = 'jpg, jpeg, gif, png, WebP';

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
        return 'seos';
    }


    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'title', 'description', 'dateCreate', 'dateUpdate','active', 'priority', 'changefreq',
            'noFollow', 'ogMeta', 'addJsonLd', 'imgPath', 'twitterMeta'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'title', 'description', 'dateCreate', 'dateUpdate','active', 'priority', 'changefreq',
            'noFollow', 'ogMeta', 'addJsonLd', 'imgPath', 'twitterMeta'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['active', 'noFollow'], 'default', 'value' => 0],
            [['ogMeta', 'addJsonLd', 'twitterMeta'], 'default', 'value' => 1],
            [['description', 'title', 'imgPath'], 'filter', 'filter' => function ($value) {
                return HtmlPurifier::process($value);
            }],
            [['active',  'noFollow', 'ogMeta', 'addJsonLd', 'twitterMeta'], 'integer'],
            [['dateCreate', 'dateUpdate', ], 'safe'],
            [['description'], 'string', 'max' => 512, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE], 'message' => 'La description SEO doit avoir au maximum 160 caractères'],
            [['priority'], 'number', 'min' => 0, 'max' => 1, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],'message' =>  'la Priorité doit-être comprise entre 0 <=> 1.0'],
            [['changefreq'], 'string', 'max' => 15, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE], 'message' => 'la Priorité doit-être comprise entre 0 <=> 1.0'],
            ['changefreq', 'in', 'range' => array_keys(self::optsFrequence())],
            [['title', 'imgPath'], 'string', 'max' => 255],
            [['title', 'description'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
                'when' => function() {
                        return (boolean)$this->active;
                }]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'active' => 'Active',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    public function beforeSave($insert)
    {
        try {
            if (empty($this->imgPath) === false) {
                $dataFile = Module::getInstance()->filePath;
                $relativeDirName = Module::getInstance()->relativeSeoImgDirName;
                $this->imgPath = $this->saveFile($dataFile, $relativeDirName, $this->imgPath);
            }
            return parent::beforeSave($insert);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
        }
    }

    public static function optsFrequence()
    {
        return [
            self::FREQUENTLY_DAILY => self::FREQUENTLY_DAILY,
            self::FREQUENTLY_MONTHLY => self::FREQUENTLY_MONTHLY,
            self::FREQUENTLY_WEEKLY => self::FREQUENTLY_WEEKLY,
        ];
    }


    public static function frequenceSuffix()
    {
        return [
            self::FREQUENTLY_DAILY => 'journalière',
            self::FREQUENTLY_MONTHLY => 'mensuelle',
            self::FREQUENTLY_WEEKLY => 'hebdomadaire',
        ];
    }

    /**
     * Gets query for [[Contents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Content::class, ['seoId' => 'id']);
    }

    public function getContent()
    {
        return $this->hasOne(Content::class, ['seoId' => 'id']);
    }

}
