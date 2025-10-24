<?php
/**
 * MenuItemBuilder.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms/helpers
 */
namespace fractalCms\helpers;

use fractalCms\components\Constant;
use fractalCms\models\Menu;
use fractalCms\models\MenuItem;
use yii\base\Component;
use yii\helpers\Url;
use Exception;
use Yii;

class MenuItemBuilder extends Component
{


    protected $iconUpdate = '  <svg width="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z" stroke="#5468ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke="#5468ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>';
    protected $iconDelete = '    <svg width="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>';
    /**
     * Build HTML menu item List
     *
     * @param Menu $menu
     *
     * @return array
     * @throws Exception
     */
    public function build(Menu $menu) : string
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            $structure = $menu->getMenuItemStructure();
            return $this->buildHtml($structure, $menu);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    protected function buildHtml($structure, Menu $menu, MenuItem $parent = null, MenuItem $lastParent = null)
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);

            $classLi = [];
            $classLi[] = 'menu-item';
            $classMargin = 'p-0';
            if($parent !== null) {
                $deep = $parent->order;
                if ($deep !== null && $deep !== 1) {
                    $classMargin = 'ps-'.$deep;
                }
                $classLi[] = $classMargin;
            }

            $options = [
                'class'=> 'list-none ',
            ];

            if ($lastParent !== null) {
                $deep = $lastParent->order;
                if ($deep !== null && $deep !== 1) {
                    $classMargin = 'ps-'.$deep;
                    $options['class'] = $options['class'] .= $classMargin;
                }
            }
            $html = Html::beginTag('ul', $options);
            foreach ($structure as $index => $data) {
                /** @var MenuItem $currentItem */
                $currentItem = $data['item'];
                if (empty($data['child']) === false) {
                    $html .= Html::beginTag('li', [
                        'class' => $classMargin,
                        'data-id' => $currentItem->id,
                        'data-menu-id' => $menu->id,
                        'data-index' => $index,
                        'draggable' => 'true',
                    ]);
                    $html .= $this->createLine($menu, $currentItem);
                    $html .= $this->buildHtml($data['child'], $menu, $currentItem, $parent);
                    $html .= Html::endTag('li');
                } else {
                    $html .= Html::tag('li',
                        $this->createLine($menu, $data['item']),
                        [
                            'class' => implode(' ', $classLi),
                            'data-id' => $currentItem->id,
                            'data-menu-id' => $menu->id,
                            'data-index' => $index,
                            'draggable' => 'true',
                        ]);
                }
            }
            $html .= Html::endTag('ul');
            return $html;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Create Line HTML menu Item
     *
     * @param Menu $menu
     * @param MenuItem $model
     * @return string
     * @throws Exception
     */
    protected function createLine(Menu $menu, MenuItem $model) : string
    {
        try {

            $line =  Html::beginTag('div', ['class' => 'row align-items-center  p-1 border mt-1 border-primary']);
            $className = [];
            $className[] = 'col-sm-6';
            $route = $model->route;
            if ($model->content instanceof \fractalCms\models\Content) {
                $route = $model->content->getRoute();
            }
            $line .= Html::tag('div', ucfirst($model->name), ['class' => implode(' ', $className)]);
            $line .= Html::tag('div', $route, ['class' => 'col-sm-3']);

            $line .= Html::beginTag('div', ['class' => 'col-sm-3']);
            $line .= Html::beginTag('div', ['class' => 'row align-items-center']);
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_UPDATE) === true)  {
                $line .= Html::a($this->iconUpdate, Url::to(['menu-item/update', 'menuId' => $menu->id, 'id' => $model->id]), ['class' => 'icon-link col', 'title' => 'Editer']);
            }
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_MENU.Constant::PERMISSION_ACTION_DELETE) === true)  {
                $line .= Html::a($this->iconDelete, Url::to(['api/menu-item/delete', 'id' => $model->id]), ['class' => 'icon-link col user-button-delete', 'title' => 'Supprimer']);
            } else {
                $line .= Html::tag('span', '', ['class' => 'col']);
            }

            $line .= Html::endTag('div');
            $line .= Html::endTag('div');
            $line .= Html::endTag('div');
            return $line;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
