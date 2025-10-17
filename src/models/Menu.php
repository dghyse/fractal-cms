<?php
/*
 * Menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\models
 */
namespace fractalCms\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use Yii;
use Exception;
/**
 * This is the model class for table "menus".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $active
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property MenuItem[] $menuItems
 */
class Menu extends \yii\db\ActiveRecord
{
    use \fractalCms\traits\Menu;

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
        return 'menus';
    }

    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'name', 'dateCreate', 'dateUpdate', 'active'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'name', 'dateCreate', 'dateUpdate', 'active'
        ];

        $scenarios[self::SCENARIO_MOVE_MENU_ITEM] = [
            'sourceMenuItemId', 'destMenuItemId', 'sourceIndex', 'destIndex'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'dateCreate', 'dateUpdate'], 'default', 'value' => null],
            [['active'], 'default', 'value' => 1],
            [['active'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_CREATE]],
            [['name'], 'unique'],
            [[ 'sourceMenuItemId', 'destMenuItemId', 'sourceIndex', 'destIndex'], 'required', 'on' => [self::SCENARIO_MOVE_MENU_ITEM]],

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
            'active' => 'Active',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    /**
     * This function insert in other level 2 menu item
     * @param MenuItem $dest
     * @param MenuItem $source
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function insertChild(MenuItem $dest, MenuItem $source) : bool
    {
        try {
            $transaction = yii::$app->db->beginTransaction();
            $countChild = $this->getMenuItems(true)->count();
            $destPathKey = $dest->pathKey;
            $sourcePathKey = $source->pathKey;
            //Simulate new pathKey for temp
            $dest->scenario = MenuItem::SCENARIO_UPDATE;
            $dest->pathKey = $this->findValidPathKey($countChild);
            $updated = false;
            if ($dest->validate() === true) {
                $dest->save(false, ['pathKey']);
                $updated = true;
            }
            if ($updated === true) {
                $source->scenario = MenuItem::SCENARIO_UPDATE;
                $source->pathKey = $this->findValidPathKey($countChild);
                if ($source->validate() === true) {
                    $source->save(false, ['pathKey', 'menuItemId']);
                    $updated = true;
                } else {
                    $updated = false;
                }
            }
            if ($updated === true) {
                //Reinit pathKey with initial changed
                $dest->scenario = MenuItem::SCENARIO_UPDATE;
                if (empty($source->menuItemId) === false) {
                    $source->menuItemId = null;
                    $sourcePathKey = $this->findValidPathKey($countChild);
                }
                $dest->pathKey = $sourcePathKey;
                $source->scenario = MenuItem::SCENARIO_UPDATE;
                $source->pathKey = $destPathKey;
                if ($dest->validate() === true && $source->validate() === true) {
                    $dest->save(false, ['pathKey']);
                    $source->save(false, ['pathKey']);
                } else {
                    $updated = false;
                }
            }

            if ($updated === true) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
            return $updated;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Gets query for [[MenuItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems($filterMainItem = false)
    {
        $query =  $this->hasMany(MenuItem::class, ['menuId' => 'id']);
        if ($filterMainItem === true) {
            $query->andWhere([MenuItem::tableName().'.menuItemId' => null]);
        }
        return $query;
    }

    /**
     * Check path key
     *
     * @param $level
     * @return string
     * @throws Exception
     */
    public function findValidPathKey($level) : string
    {
        try {
            $pathKey = $this->id.'.'.$level;
            $itemQuery = MenuItem::find()->where(['pathKey' => $pathKey]);
            if ($itemQuery->count() > 0) {
                $pathKey = $this->findValidPathKey($level + 1);
            }
            return $pathKey;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Get menu item child
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemChild()
    {
        $query =  $this->hasMany(MenuItem::class, ['menuId' => 'id']);
        $regex = '^'.$this->id.'\.\w$';
        return $query->andWhere(MenuItem::tableName().'.pathKey regexp :regex', [':regex' => $regex]);
    }

    /**
     * Build menu item structure
     *
     * [
     *     [
     *          'item' => [[MenuItem]],
     *          'child' => [
     *                        [
     *                          'item' => [[MenuItem]],
     *                          'child' => [
     *                                      ../..
     *                          ]
 *                          ],
     *                      ../..
 *              ]
     *      ],
     * ../..
     * ]
     *
     * @return array
     * @throws Exception
     */
    public function getMenuItemStructure() : array
    {
        try {
            return $this->buildStructure($this->getMenuItems(true)->orderBy(['pathKey' => SORT_ASC]));
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function buildStructure(ActiveQuery $itemMenuQuery) : array
    {
        try {
            $structure  = [];
            /** @var MenuItem $menuItem */
            foreach ($itemMenuQuery->each() as $menuItem) {
                $part = [
                    'item' => $menuItem,
                ];

                $subMenuQuery = $menuItem->getMenuItems();
                if ($subMenuQuery->count() > 0 ) {
                    $part['child'] = $this->buildStructure($subMenuQuery);
                }
                $structure[] = $part;
            }
            return $structure;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }



}
