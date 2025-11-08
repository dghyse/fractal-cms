<?php


namespace Tests\Unit;

use fractalCms\components\Constant;
use fractalCms\helpers\Cms;
use fractalCms\models\Menu;
use fractalCms\models\MenuItem;
use fractalCms\models\Parameter;
use fractalCms\models\User;
use fractalCms\models\Seo;
use Tests\Support\UnitTester;

class ModelTest extends \Codeception\Test\Unit
{

    protected static $itemConfigTitle = '"titre": {
    "title": {
      "type": "string",
      "title": "Titre"
    },
    "icon": {
      "type": "file",
      "title": "image en 64x64 (icon placé à droite du titre)",
      "accept": "png, jpeg, jpg, webp"
    }
  }';
    
    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testUser()
    {
        $user = User::createUser(
            Constant::ROLE_ADMIN,
        'test.fr',
        'test',
        'test',
        'test');
        $validate = $user->validate();
        $this->assertFalse($validate);
        $this->assertTrue($user->hasErrors());
        $this->assertArrayHasKey('email', $user->errors);
        $this->assertArrayHasKey('tmpPassword', $user->errors);
        $user = User::createUser(
            Constant::ROLE_ADMIN,
            'admin@webcraft.fr',
            'c4wL2wH0C2BydUwG',
            'admin',
            'web');
        $this->assertFalse($user->hasErrors());

        $userDb = User::find()->andWhere(['email' => 'c4wL2wH0C2BydUwG'])->one();
        $this->assertNull($userDb);

        $userDb = User::find()->andWhere(['email' => 'admin@webcraft.fr'])->one();
        $this->assertNotNull($userDb);
        $valid = $userDb->validatePassword('c4wL2wH0C2BydUwG');
        $this->assertTrue($valid);

        $id = $userDb->getId();
        $this->assertEquals($id, $userDb->id);
        $authKey = $userDb->getAuthKey();
        $this->assertIsString($authKey);
        $authkeyOk = $userDb->validateAuthKey('test');
        $this->assertFalse($authkeyOk);
        $identity = User::findIdentity($userDb->id);
        $this->assertNotNull($identity);
        $identity = User::findIdentityByAccessToken('test');
        $this->assertNull($identity);
        $name = $userDb->getInitials();
        $this->assertEquals('AW', $name);
        $userAttributes = [
          'email' => 'test@webcraft.fr',
          'tmpPassword' => 'c4wL2wH0C2BydUwG',
            'lastname' => 'test',
            'firstname' => 'test',
        ];

        $user = new User(['scenario' => User::SCENARIO_CREATE]);
        $user->buildAuthRules();
        $load = $user->load($userAttributes, '');
        $user->hashPassword();
        $passwordIsHash = preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $user->password);
        $this->assertEquals(1, $passwordIsHash);

        $this->assertTrue($load);

    }

    public function testParameter()
    {
        $parameter = new Parameter(['scenario' => Parameter::SCENARIO_CREATE]);
        $parameter->group = 'TEST';
        $parameter->name = 'TEST1';
        $parameter->value = 'test';
        $this->assertTrue($parameter->save());
        $value = Cms::getParameter('TEST', 'TEST1');
        $this->assertEquals('test', $value);
        $parameter = new Parameter(['scenario' => Parameter::SCENARIO_CREATE]);
        $parameter->group = 'CONTENT';
        $parameter->name = 'MAIN';
        $parameter->value = 'test';
        $this->assertFalse($parameter->save());
    }


    public function testMenu()
    {
        $menu = new Menu(['scenario' => Menu::SCENARIO_CREATE]);
        $menu->name = 'header';
        $this->assertFalse($menu->save());
        $menu = Menu::find()->where(['name' => 'header'])->one();
        $this->assertNotNull($menu);
        $menu = new Menu(['scenario' => Menu::SCENARIO_CREATE]);
        $menu->name = 'test';
        $this->assertTrue($menu->save());
        $menu = Menu::find()->where(['name' => 'test'])->one();
        $this->assertNotNull($menu);

        $menuItem = new MenuItem(['scenario' => MenuItem::SCENARIO_CREATE]);
        $menuItem->name = 'menuItem 1';
        $this->assertFalse($menuItem->save());
        $menuItem->route = '/content/index';
        $menuItem->menuId = $menu->id;
        $this->assertTrue($menuItem->save());
        $menuItem->attach();
        $menuItem2 = new MenuItem(['scenario' => MenuItem::SCENARIO_CREATE]);
        $menuItem2->name = 'menuItem 2';
        $menuItem2->route = '/content/index';
        $menuItem2->menuId = $menu->id;
        $this->assertTrue($menuItem2->save());
        $menuItem2->attach();
        $success = $menu->move($menuItem, $menuItem2);
        $this->assertTrue($success);
        $success = $menuItem2->move($menuItem);
        $this->assertTrue($success);
        $menuDb = $menuItem->getMenu()->one();
        $this->assertNotNull($menuDb);
        $array = $menuItem->getMenuItems()->all();
        $this->assertEmpty($array);
        $content = $menuItem->getContent()->one();
        $this->assertNull($content);
        $menuItemDb = $menuItem->getParentMenuItem()->one();
        $this->assertNull($menuItemDb);
        $structure = $menu->getMenuItemStructure();
        $this->assertIsArray($structure);
        $menuItemQuery = $menu->getMenuItems();
        $structure = $menu->buildStructure($menuItemQuery);
        $this->assertIsArray($structure);
    }
    
    public function testSeo()
    {
        $seo = new Seo(['scenario' => Seo::SCENARIO_CREATE]);
        $seo->active = 1;
        $seo->title = 'titre test';
        $seo->description = 'description';
        $seo->changefreq = 'test';
        $this->assertFalse($seo->save());
        $seo->changefreq = 'monthly';
        $this->assertTrue($seo->save());
        $seo->refresh();
        $contents = $seo->getContents()->all();
        $this->assertEmpty($contents);
        $content = $seo->getContent()->one();
        $this->assertNull($content);
    }
}
