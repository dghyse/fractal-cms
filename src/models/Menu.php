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
            return $this->buildStructure($this->getMenuItems(true));
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
