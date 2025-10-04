<?php
/**
 * Menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package cms/widgets
 */
namespace fractalCms\widgets;

use fractalCms\helpers\Menu as MenuHelper;
use yii\base\Widget;
use Yii;
use yii\helpers\Url;
use Exception;
use yii\helpers\Html;

class Menu extends Widget
{


    protected MenuHelper $menu;
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::debug('Trace: '.__METHOD__, __METHOD__);
        ob_start();
    }

    public function __construct(MenuHelper $menu, $config = [])
    {
        $this->menu = $menu;
        parent::__construct($config);
    }


    public function run()
    {
        try {
            Yii::debug('Trace: '.__METHOD__, __METHOD__);
            $content = ob_get_clean();
            return $this->render(
                'menu', [
                    'data' => $this->build(),
                ]
            );
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }


    protected function build() : string
    {
        try {
            Yii::debug('Trace: '.__METHOD__, __METHOD__);
            $data = $this->menu->get();
            $html = '';
            foreach ($data as $item){
                $classItem = 'nav-item';
                $classLinkItem = 'nav-link';
                $optionsClass = (empty($item['optionsClass']) === false) ? $item['optionsClass'] : [];
                $title = (empty($item['title']) === false) ? $item['title'] : null;
                $url = (empty($item['url']) === false) ? $item['url'] : '#';
                $icon = (empty($item['icon']) === false) ? $item['icon'] : null;
                $children = [];
                $hasChildren = false;
                $linkOptions = [];
                $linkOptions['href'] = $url;
                if (empty($item['children']) === false) {
                    $children = $item['children'];
                    $classItem .= ' dropdown';
                    $hasChildren = true;
                    $classLinkItem .= 'dropdown-toggle';
                    $linkOptions['role'] = 'button';
                    $linkOptions['data-bs-toggle'] = 'dropdown';
                    $linkOptions['aria-expanded'] = 'false';
                }
                $classLinkItem .= ' '.implode(' ', $optionsClass);
                $linkOptions['class'] = $classLinkItem;

                $html .= Html::beginTag('li', ['class' => $classItem]);
                $html .= Html::beginTag('a', $linkOptions);
                $html .= $icon.$title;
                $html .= Html::endTag('a');
                if ($hasChildren === true) {
                    $html .= Html::beginTag('ul', ['class' => 'dropdown-menu']);
                }
                foreach ($children as $childItem) {
                    $html .= Html::tag('li', Html::a($childItem['title'], $childItem['url']));
                }
                if ($hasChildren === true) {
                    $html .= Html::endTag('ul');
                }
                $html .= Html::endTag('li');
            }
            return $html;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
