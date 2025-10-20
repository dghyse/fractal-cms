<?php
/*
 * MenuItem.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
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
 * @property string $route
 * @property string $name
 * @property int|null $order
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 *
 * @property Menu $menu
 * @property MenuItem $parentMenuItem
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
            'menuId', 'menuItemId', 'order', 'dateCreate', 'dateUpdate', 'name', 'contentId', 'route'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'menuId', 'menuItemId', 'order', 'dateCreate', 'dateUpdate', 'name', 'contentId', 'route'
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
            [['menuId', 'menuItemId', 'contentId'], 'integer'],
            [['order'], 'number'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['name', 'route'], 'string', 'max' => 255],
            [['name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['contentId'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE], 'message' => 'Sélectionné une cible CMS', 'when' => function() {
                return empty($this->route);
            }],
            [['route'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE], 'message' => 'Sélectionné une cible Interne', 'when' => function() {
                return empty($this->contentId);
            }],
            [['menuItemId'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItem::class, 'targetAttribute' => ['menuItemId' => 'id']],
            [['menuId'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::class, 'targetAttribute' => ['menuId' => 'id']],
            [['contentId'], 'exist', 'skipOnError' => true, 'targetClass' => Content::class, 'targetAttribute' => ['contentId' => 'id']],
        ];
    }

    /**
     * Attach menu item
     *
     * @return void
     * @throws Exception
     */
    public function attach() : void
    {
        try {
            $nbChild = false;
            if (empty($this->menuItemId) === false) {
                $parent = self::findOne($this->menuItemId);
                $nbChild = $parent->getMenuItems()->count();
            } else {
                $parent = Menu::findOne($this->menuId);
                if ($parent !== null) {
                    $nbChild = $parent->getMenuItems(true)
                        ->andWhere(['not', ['id' => $this->id]])->count();
                }
            }
            if ($nbChild !== false) {
                $this->order = $nbChild + 1;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * InsertChild
     *
     * @param MenuItem $source
     * @return bool
     * @throws \yii\db\Exception
     */
    public function move(MenuItem $source) : bool
    {
        try {
            $command = Yii::$app->db->createCommand();
            $updated = false;
            if (empty($this->menuItemId) === true) {
                /** @var Menu $menu */
                $menu = $this->getMenu()->one();
                if ($menu !== null) {
                    $updated = $menu->move($this, $source);
                    if ($updated === true) {
                        $menu->reorder();
                    }
                }
            } else {
                //Update Source
                $command->update(
                    static::tableName(),
                    [
                        'menuItemId' => $this->menuItemId,
                        'order' => $this->order
                    ],
                    'id=:id',
                    [':id' => $source->id]
                )->execute();

                $command->update(
                    static::tableName(),
                    [
                        'order' => $this->order + 0.1
                    ],
                    'id=:id',
                    [':id' => $this->id]
                )->execute();
                $updated = true;
            }
            return $updated;
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
    public function getParentMenuItem()
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
        return $this->hasMany(MenuItem::class, ['menuItemId' => 'id'])->orderBy(['menuItemId' => SORT_ASC, 'order' => SORT_ASC]);
    }

}
