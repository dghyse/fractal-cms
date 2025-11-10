<?php
/**
 * Tag.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\models
 */
namespace fractalCms\models;

use fractalCms\interfaces\ItemInterface;
use fractalCms\Module;
use fractalCms\traits\Item as TraitItem;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Exception;
use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $slugId
 * @property int|null $seoId
 * @property int|null $configTypeId
 * @property int|null $active
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property ConfigType $configType
 * @property ContentTag[] $contentTags
 * @property Content[] $contents
 * @property Item[] $items
 * @property Seo $seo
 * @property Slug $slug
 * @property TagItem[] $tagItems
 */
class Tag extends \yii\db\ActiveRecord implements ItemInterface
{
    use TraitItem;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    public $items;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags';
    }

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
    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'name', 'slugId', 'seoId', 'configTypeId', 'dateCreate', 'dateUpdate', 'active', 'type',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'name', 'slugId','seoId', 'configTypeId','dateCreate', 'dateUpdate', 'active', 'type', 'items'
        ];
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slugId', 'seoId', 'configTypeId', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['active'], 'default', 'value' => 0],
            [['slugId', 'seoId', 'configTypeId', 'active'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['name'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['configTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => ConfigType::class, 'targetAttribute' => ['configTypeId' => 'id']],
            [['seoId'], 'exist', 'skipOnError' => true, 'targetClass' => Seo::class, 'targetAttribute' => ['seoId' => 'id']],
            [['slugId'], 'exist', 'skipOnError' => true, 'targetClass' => Slug::class, 'targetAttribute' => ['slugId' => 'id']],
            [['configTypeId'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
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
            'seoId' => 'Seo ID',
            'configTypeId' => 'Config Type ID',
            'active' => 'Active',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    public function getRoute() : string | false
    {
        try {
            $prefix = Module::getInstance()->getContextId();
            return '/'.$prefix.'/tag-'.$this->id;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Attach Item
     *
     * @param Item $item
     * @return TagItem
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function attachItem(Item $item) : TagItem
    {
        try {
            $tagItem = TagItem::find()
                ->andWhere(['tagId' => $this->id, 'itemId' => $item->id])->one();
            if ($tagItem === null) {
                $itemsCount = $this->getItems()->count();
                $tagItem = Yii::createObject(TagItem::class);
                $tagItem->tagId = $this->id;
                $tagItem->itemId = $item->id;
                $tagItem->order = $itemsCount;
                $tagItem->save();
            }
            return $tagItem;
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
            return TagItem::deleteAll(['tagId' => $this->id, 'itemId' => $item->id]);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    public function reOrderItems() : void
    {
        try {
            $tagItemsQuery = TagItem::find()
                ->andWhere(['tagId' => $this->id])->orderBy(['order' => SORT_ASC]);
            $index = 0;
            /** @var ContentItem $contentItem */
            foreach ($tagItemsQuery->each() as $tagItem) {
                $tagItem->scenario = TagItem::SCENARIO_UPDATE;
                $tagItem->order = $index;
                if ($tagItem->validate() === true) {
                    $tagItem->save();
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
            $relationDeleted =  TagItem::deleteAll(['itemId' => $item->id, 'tagId' => $this->id]);
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
     * Gets query for [[ConfigType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfigType()
    {
        return $this->hasOne(ConfigType::class, ['id' => 'configTypeId']);
    }

    /**
     * Gets query for [[ContentTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContentTags()
    {
        return $this->hasMany(ContentTag::class, ['tagId' => 'id']);
    }

    /**
     * Gets query for [[Contents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Content::class, ['id' => 'contentId'])->viaTable('contentTags', ['tagId' => 'id']);
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        $query = Item::find()->andWhere(['tagId' => $this->id])
            ->innerJoin(TagItem::tableName(), 'tagId='.$this->id.' and itemId=items.id');
        $query->orderBy(['order' => SORT_ASC]);
        return $query;
    }

    /**
     * Gets query for [[Seo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::class, ['id' => 'seoId']);
    }

    /**
     * Gets query for [[Slug]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlug()
    {
        return $this->hasOne(Slug::class, ['id' => 'slugId']);
    }

    /**
     * Gets query for [[TagItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagItems()
    {
        return $this->hasMany(TagItem::class, ['tagId' => 'id']);
    }

}
