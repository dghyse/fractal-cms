<?php
/*
 * Menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\models
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
            'sourceMenuItemId', 'destMenuItemId'
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
            [[ 'sourceMenuItemId', 'destMenuItemId'], 'required', 'on' => [self::SCENARIO_MOVE_MENU_ITEM]],

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
     * Move source in place of destination
     *
     * @param MenuItem $dest
     * @param MenuItem $source
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function move(MenuItem $dest, MenuItem $source) : bool
    {
        try {
            $transaction = yii::$app->db->beginTransaction();
            $destOrder = $dest->order;
            $sourceOrder = $source->order;
            $dest->scenario = MenuItem::SCENARIO_UPDATE;
            $source->scenario = MenuItem::SCENARIO_UPDATE;
            $attributesToSave = ['order'];
            if (empty($source->menuItemId) === false) {
                $source->menuItemId = null;
                $attributesToSave[] = 'menuItemId';
                $sourceOrder = $destOrder + 0.1;
            }
            $dest->order = $sourceOrder;
            $source->order = $destOrder;
            $updated = false;
            if ($dest->validate() === true && $source->validate() === true) {
                $dest->save(false, ['order']);
                $source->save(false, $attributesToSave);
                $updated = true;
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
            return $this->buildStructure($this->getMenuItems(true)->orderBy(['order' => SORT_ASC]));
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

    /**
     * Reorder menu items
     *
     * @param boolean $filterMainItem
     * @param integer $startIndex
     * @return void
     * @throws Exception
     */
    public function reorder(bool $filterMainItem = false, int $startIndex = 1) : void
    {
        try {
            $command = Yii::$app->db->createCommand();
            $index = $startIndex;
            $menuItemId = null;
            $menuItemQuery = $this->getMenuItems($filterMainItem)->orderBy(['menuItemId' => SORT_ASC, 'order' => SORT_ASC]);
            /** @var MenuItem $menuItem */
            foreach ($menuItemQuery->each() as $menuItem) {
                if ($menuItem->menuItemId !== null && $menuItem->menuItemId != $menuItemId) {
                    $menuItemId = $menuItem->menuItemId;
                    $index = $startIndex;
                }
                $command->update('{{%menuItems}}',['order' => $index], 'id=:id', [':id' => $menuItem->id]);
                $index += 1;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
