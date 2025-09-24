<?php
/*
 * MenuItem.php
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
 * This is the model class for table "menuItems".
 *
 * @property int $id
 * @property int|null $menuId
 * @property int|null $menuItemId
 * @property int|null $contentId
 * @property string $pathKey
 * @property string $name
 * @property int|null $order
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Menu $menu
 * @property MenuItem $menuItem
 * @property Content $content
 * @property MenuItem[] $menuItems
 */
class MenuItem extends \yii\db\ActiveRecord
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
        return 'menuItems';
    }

    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'menuId', 'menuItemId', 'order', 'dateCreate', 'dateUpdate', 'name', 'contentId', 'pathKey'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'menuId', 'menuItemId', 'order', 'dateCreate', 'dateUpdate', 'name', 'contentId', 'pathKey'
        ];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menuId', 'menuItemId', 'order', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['menuId', 'menuItemId', 'order', 'contentId'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['pathKey'], 'unique'],
            [['name', 'contentId', 'pathKey'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['menuItemId'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItem::class, 'targetAttribute' => ['menuItemId' => 'id']],
            [['menuId'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::class, 'targetAttribute' => ['menuId' => 'id']],
            [['contentId'], 'exist', 'skipOnError' => true, 'targetClass' => Content::class, 'targetAttribute' => ['contentId' => 'id']],
        ];
    }

    public function attach() : void
    {
        try {
            $brothersCount = false;
            if (empty($this->menuItemId) === false) {
                $parentMenutItem = self::findOne($this->menuItemId);
                if ($parentMenutItem !== null) {
                    $parentPathKey = $parentMenutItem->pathKey;
                    if (empty($parentPathKey) === false) {
                        $brothersCount = $parentMenutItem->getMenuItems()
                            ->andWhere(['not', ['id' => $this->id]])->count();
                    }
                }
            } else {
                $parentMenu = Menu::findOne($this->menuId);
                if ($parentMenu !== null) {
                    $parentPathKey = $this->menuId;
                    $brothersCount = $parentMenu->getMenuItems(true)
                        ->andWhere(['not', ['id' => $this->id]])->count();
                }
            }

            if ($brothersCount !== false) {
                $newPathKey = $parentPathKey.'.'.($brothersCount + 1);
                $newPathKey = $this->checkPAthKey($newPathKey, $parentPathKey, ($brothersCount + 1));
                $this->pathKey = $newPathKey;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function checkPAthKey($newPathKey, $parentPathKey, $brothersCount) : string
    {
        try {
            $computePathKey = $newPathKey;
            $pathCount = static::find()->andWhere(['pathKey' => $newPathKey])->count();
            if ($pathCount > 0) {
                $computePathKey = $parentPathKey.'.'.($brothersCount + 1);
                $computePathKey = $this->checkPAthKey($computePathKey, $parentPathKey, $brothersCount += 1);
            }
            return $computePathKey;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function detach() : void
    {
        try {
            $parentPAthKey = $this->menuId.'.';
            if (empty($this->menuItemId) === false) {
                $parentMenutItem = self::findOne($this->menuItemId);
                $brothersQuery = $parentMenutItem->getMenuItems();
                $parentPAthKey = $parentMenutItem->pathKey.'.';
            } else {
                $parentMenu = Menu::findOne($this->menuId);
                if ($parentMenu !== null) {
                    $brothersQuery = $parentMenu->getMenuItems()->andWhere(['menuItemId' => null]);
                }
            }
            $index = 1;

            /** @var MenuItem $menuITem */
            foreach ($brothersQuery->each() as $menuITem) {
                $menuITem->pathKey = $parentPAthKey.($index);
                $menuITem->dateUpdate = new Expression('NOW()');
                $menuITem->save(false, ['pathKey', 'dateUpdate']);
                //If has sub items reoder
                $subIndex = $this->rebuildPathKey($menuITem, $menuITem->pathKey);
                $index += 1;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function rebuildPathKey(MenuItem $menuItem, $parentPathKey)
    {
        try {
            $subMenuItems = $menuItem->getMenuItems();
            $subItemIndex = 1;
            /** @var MenuItem $subMenuItem */
            foreach ($subMenuItems->each() as $subMenuItem) {
                $subMenuItem->pathKey = $parentPathKey.'.'.$subItemIndex;
                $subMenuItem->dateUpdate = new Expression('NOW()');
                $subMenuItem->save(false, ['pathKey', 'dateUpdate']);
                $newIndex = $this->rebuildPathKey($subMenuItem, $subMenuItem->pathKey);
                $subItemIndex += 1;
            }
            return $subItemIndex;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function getDeep() :int | null
    {
        try {
            $pathKey = $this->pathKey;
            $deep = null;
            if (empty($pathKey) === false) {
                $splitKey = explode('.', $pathKey);
                $deep = count($splitKey);
            }
            return $deep;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menuId' => 'Menu ID',
            'menuItemId' => 'Menu Item ID',
            'name' => 'Name',
            'order' => 'Order',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    /**
     * Gets query for [[Menu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::class, ['id' => 'menuId']);
    }

    /**
     * Gets query for [[MenuItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::class, ['id' => 'menuItemId']);
    }

    /**
     * Gets query for [[MenuItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(Content::class, ['id' => 'contentId']);
    }

    /**
     * Gets query for [[MenuItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::class, ['menuItemId' => 'id']);
    }

}
