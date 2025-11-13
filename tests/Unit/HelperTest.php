<?php


namespace Tests\Unit;

use fractalCms\helpers\Cms;
use fractalCms\helpers\Menu;
use fractalCms\models\Content;
use Tests\Support\UnitTester;
use function PHPUnit\Framework\assertEquals;

class HelperTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {

    }

    // tests
    public function testCms()
    {
        $sections = Cms::buildSections();
        $this->assertNotEmpty($sections);
        $struct = Cms::getStructure();
        $this->assertNotEmpty($struct);
        $struct = Cms::getTags();
        $this->assertNotEmpty($struct);
        $controllers = Cms::getControllerStructure();
        $this->assertNotEmpty($controllers);
        $camel = 'MonEspaceClient';
        $id = Cms::camelToId($camel);
        $this->assertEquals('mon-espace-client', $id);
        $string = 'mon espace client';
        $spacing = Cms::insertIndivisibleSpace($string);
        $this->assertEquals('mon&nbsp;espace&nbsp;client', $spacing);
        $contentQuery = Content::find()->where(['active' => 1]);
        $contentSruct = Cms::buildStructure($contentQuery);
        $this->assertNotEmpty($contentSruct);
        $forms = Cms::getForms();
        $this->assertEquals(1, $this->count($forms));
        $routes = Cms::getInternCmsRoutes();
        $this->assertEmpty($routes);
        $menu = \fractalCms\models\Menu::find()->where(['name' => 'header'])->one();
        $this->assertNotNull($menu);
        $menuStruct = Cms::getMenuItemStructure($menu->id);
        $this->assertNotEmpty($menuStruct);
        $configItems = Cms::getConfigItems();
        $this->assertNotEmpty($configItems);
        $parameter = Cms::getParameter('CONTENT', 'MAIN');
        $this->assertEquals(1, $parameter);
        $html = '<p>truc&nbsp;machin</p><p>&nbsp;&nbsp;</p><p><br/><br></p>';
        $newHtml = Cms::cleanHtml($html);
        $this->assertEquals('<p>truc&nbsp;machin</p>', $newHtml);


    }
}
