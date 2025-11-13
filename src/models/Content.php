<?php
/**
 * Content.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\models
 */
namespace fractalCms\models;

use Exception;
use fractalCms\interfaces\ItemInterface;
use fractalCms\Module;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use fractalCms\traits\Item as TraitItem;

/**
 * This is the model class for table "contents".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $slugId
 * @property int|null $seoId
 * @property int|null $configTypeId
 * @property string $type
 * @property string|null $pathKey
 * @property int|null $active
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property ConfigType $configType
 * @property Slug $slug
 * @property Seo $seo
 * @property ContentItem[] $contentItems
 * @property Item[] $items
 */
class Content extends \yii\db\ActiveRecord implements ItemInterface
{

    use TraitItem;
    /**
     * ENUM field values
     */
    const TYPE_SECTION = 'section';
    const TYPE_ARTICLE = 'article';

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_INIT = 'init';
    public $items;
    public $formTags;

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

    public $parentPathKey;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contents';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'name', 'slugId', 'seoId', 'configTypeId', 'pathKey', 'dateCreate', 'dateUpdate', 'active', 'type', 'parentPathKey', 'formTags'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'name', 'slugId','seoId', 'configTypeId', 'pathKey', 'dateCreate', 'dateUpdate', 'active', 'type', 'parentPathKey', 'items', 'formTags'
        ];
        $scenarios[self::SCENARIO_INIT] = [
            'name', 'slugId','seoId', 'configTypeId', 'pathKey', 'dateCreate', 'dateUpdate', 'active', 'type', 'parentPathKey', 'formTags'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slugId', 'configTypeId', 'pathKey', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['active'], 'default', 'value' => 0],
            [['slugId','seoId', 'configTypeId', 'active'], 'integer'],
            [['type'], 'string'],
            [['type', 'name'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE, self::SCENARIO_INIT]],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['name', 'pathKey', 'parentPathKey'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::optsType())],
            [['name'], 'unique'],
            [['type', 'pathKey'], 'unique', 'targetAttribute' => ['type', 'pathKey']],
            [['configTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => ConfigType::class, 'targetAttribute' => ['configTypeId' => 'id']],
            [['configTypeId'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['parentPathKey'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE], 'when' => function() {
                return $this->pathKey !== '1';
            }],
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
            'slugId' => 'Slug ID',
            'configTypeId' => 'Config Type ID',
            'type' => 'Type',
            'pathKey' => 'Path Key',
            'active' => 'Active',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        if (empty($this->pathKey) === false) {
            $parentPathKey = substr($this->pathKey, 0,strlen($this->pathKey) -1);
            $this->parentPathKey = trim($parentPathKey, '.');
        }
        $contentTagQuery = ContentTag::find()->andWhere(['contentId' => $this->id]);
        /** @var ContentTag $contentTag */
        foreach ($contentTagQuery->each() as $contentTag) {
            $this->formTags[] = $contentTag->tagId;
        }
    }

    /**
     * Manage tags
     *
     * @return void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function manageTags() : void
    {
        try {
            ContentTag::deleteAll(['contentId' => $this->id]);
            if (is_array($this->formTags) === false) {
                $this->formTags = [$this->formTags];
            }
            if (empty($this->formTags) === false) {
                foreach ($this->formTags as $tagId) {
                    $tag = Tag::findOne($tagId);
                    if ($tag !== null) {
                        $contentTag = Yii::createObject(ContentTag::class);
                        $contentTag->scenario = ContentTag::SCENARIO_CREATE;
                        $contentTag->tagId = $tag->id;
                        $contentTag->contentId = $this->id;
                        if ($contentTag->validate() === true) {
                            $contentTag->save();
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Attach Item
     *
     * @param Item $item
     * @return ContentItem
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function attachItem(Item $item) : ContentItem
    {
        try {
            $contentItem = ContentItem::find()
                ->andWhere(['contentId' => $this->id, 'itemId' => $item->id])->one();
            if ($contentItem === null) {
                $itemsCount = $this->getItems()->count();
                $contentItem = Yii::createObject(ContentItem::class);
                $contentItem->contentId = $this->id;
                $contentItem->itemId = $item->id;
                $contentItem->order = $itemsCount;
                $contentItem->save();
            }
            return $contentItem;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Detach Item of Content
     *
     * @param Item $item
     * @return int
     * @throws Exception
     */
    public function detachItem(Item $item) : int
    {
        try {
            return ContentItem::deleteAll(['contentId' => $this->id, 'itemId' => $item->id]);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Reorder items
     *
     * @return void
     * @throws \yii\db\Exception
     */
    public function reOrderItems() : void
    {
        try {
            $contentItemsQuery = ContentItem::find()
                ->andWhere(['contentId' => $this->id])->orderBy(['order' => SORT_ASC]);
            $index = 0;
            /** @var ContentItem $contentItem */
            foreach ($contentItemsQuery->each() as $contentItem) {
                $contentItem->scenario = ContentItem::SCENARIO_UPDATE;
                $contentItem->order = $index;
                if ($contentItem->validate() === true) {
                    $contentItem->save();
                }
                $index += 1;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * @param Item $item
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteItem(Item $item) : int
    {
        try {
            $relationDeleted =  ContentItem::deleteAll(['itemId' => $item->id, 'contentId' => $this->id]);
            if ($relationDeleted > 0) {
                $item->deleteFilesDir();
                $item->delete();
            }
            return  $relationDeleted;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    /**
     * Gets query for [[ConfigType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfigType()
    {
        return $this->hasOne(ConfigType::class, ['id' => 'configTypeId']);
    }

    /**
     * Gets query for [[ConfigType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlug()
    {
        return $this->hasOne(Slug::class, ['id' => 'slugId']);
    }

    /**
     * Gets query for [[Seo]].
     *
     * @return ActiveQuery
     *
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::class, ['id' => 'seoId']);
    }

    /**
     * Gets query for [[ContentItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContentItems()
    {
        return $this->hasMany(ContentItem::class, ['contentId' => 'id']);
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        $query = Item::find()->andWhere(['contentId' => $this->id])
            ->innerJoin(ContentItem::tableName(), 'contentId='.$this->id.' and itemId=items.id');
        $query->orderBy(['order' => SORT_ASC]);
        return $query;
    }

    /**
     * Get item with config
     *
     * @param $configItemId
     *
     * @return Item|null
     * @throws Exception
     */
    public function getItemByConfigId($configItemId) : Item | null
    {
        try {
           return $this->getItems()->andWhere(['configItemId' => $configItemId])->one();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get Level of element
     *
     * @return int|null
     * @throws Exception
     */
    public function getLevel() :int | null
    {
        try {
            $pathKey = $this->pathKey;
            $level = null;
            if (empty($pathKey) === false) {
                $splitKey = explode('.', $pathKey);
                $level = count($splitKey);
            }
            return $level;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get children
     *
     * @param $isActive
     * @param $withSubSection
     * @return ActiveQuery
     * @throws Exception
     */
    public function getChildrens($isActive = false, $withSubSection = false)
    {
        try {
            $query = self::find();
            $cleanPath = trim($this->pathKey).'.';
            $query->andWhere(self::tableName().'.pathKey like \''.$cleanPath.'%\'');
            if ($withSubSection === false) {
                $query->andWhere(self::tableName().'.pathKey not like \''.$cleanPath.'%.%\'');
            }
            if ($isActive === true) {
                $query->andWhere(['active' => 1]);
            }
            $query->andWhere(['type' => static::TYPE_SECTION]);
            return $query;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get children type article
     *
     * @param $isActive
     * @return ActiveQuery
     * @throws Exception
     */
    public function getArticles($isActive = false)
    {
        try {
            $query = self::find();
            $cleanPath = trim($this->pathKey).'.';
            $query->andWhere(self::tableName().'.pathKey like \''.$cleanPath.'%\'');
            $query->andWhere(self::tableName().'.pathKey not like \''.$cleanPath.'%.%\'');
            if ($isActive === true) {
                $query->andWhere(['active' => 1]);
            }
            $query->andWhere(['type' => static::TYPE_ARTICLE]);
            return $query;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get parent
     *
     * @return array|\yii\db\ActiveRecord|\yii\db\T|null
     * @throws Exception
     */
    public function getParent()
    {
        try {

            $query = self::find();
            $parent = null;
            if (empty($this->pathKey) === false) {
                $parentPathKey = substr($this->pathKey, 0,strlen($this->pathKey) -1);
                $parentPathKey = trim($parentPathKey, '.');
                $query->andWhere(['pathKey' => $parentPathKey]);
                $parent = $query->one();
            }
            return $parent;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get parents
     *
     * @return ActiveQuery
     * @throws Exception
     */
    public function getParents() : ActiveQuery
    {
        try {
            /**
             * SELECT *
             * FROM contents
             * WHERE '1.1.1.1' LIKE CONCAT(pathKey, '.%')
             * ORDER BY pathKey;
             *
             */
            $query = self::find();
            $query->andWhere(':pathKey like CONCAT(pathKey, \'.%\')', [':pathKey' => $this->pathKey]);
            return $query;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Find first item by Id
     *
     * @param $itemId
     * @return Item|null
     * @throws Exception
     */
    public function findFirstItemById($itemId) : Item | null
    {
        try {
           return $this->getItems()->andWhere(['configItemId' => $itemId])->one();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Attach Content
     *
     * @return void
     * @throws Exception
     */
    public function attach() : void
    {
        try {

            if (empty($this->parentPathKey) === false) {
                $query = self::find();
                $query->andWhere(['pathKey' => $this->parentPathKey]);
                /** @var Content $parent */
                $parent = $query->one();
                if ($parent !== null) {
                    $brothersCount = null;
                    switch ($this->type) {
                        case static::TYPE_SECTION:
                            $brothersCount = $parent->getChildrens()->count();
                            break;
                        case static::TYPE_ARTICLE:
                            $brothersCount = $parent->getArticles()->count();
                            break;
                    }
                    if ($brothersCount !== null) {
                        $newPathKey = $parent->pathKey .'.'.($brothersCount + 1);
                        $newPathKey = $this->checkPathKey($newPathKey, $parent->pathKey, ($brothersCount + 1));
                        $this->pathKey = $newPathKey;
                    }
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Check path key
     *
     * @param $newPathKey
     * @param $parentPathKey
     * @param $brothersCount
     * @return string
     * @throws Exception
     */
    public function checkPathKey($newPathKey, $parentPathKey, $brothersCount) : string
    {
        try {
            $computePathKey = $newPathKey;
            $pathCount = static::find()->andWhere(['pathKey' => $newPathKey, 'type' => $this->type])->count();
            if ($pathCount > 0) {
                $computePathKey = $parentPathKey.'.'.($brothersCount + 1);
                $computePathKey = $this->checkPathKey($computePathKey, $parentPathKey, $brothersCount);
            }
            return $computePathKey;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * get route
     *
     * @return string|false
     * @throws Exception
     */
    public function getRoute() : string | false
    {
        try {
            $prefix = Module::getInstance()->getContextId();
            return '/'.$prefix.'/content-'.$this->id;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_SECTION => 'Section',
            self::TYPE_ARTICLE => 'Article',
        ];
    }

    /**
     * @return string
     */
    public function displayType()
    {
        return self::optsType()[$this->type];
    }

    /**
     * @return bool
     */
    public function isTypeSection()
    {
        return $this->type === self::TYPE_SECTION;
    }

    public function setTypeToSection()
    {
        $this->type = self::TYPE_SECTION;
    }

    /**
     * @return bool
     */
    public function isTypeArticle()
    {
        return $this->type === self::TYPE_ARTICLE;
    }

    /**
     * set type article
     *
     * @return void
     */
    public function setTypeToArticle()
    {
        $this->type = self::TYPE_ARTICLE;
    }
}
