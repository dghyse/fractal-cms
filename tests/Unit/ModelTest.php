<?php


namespace Tests\Unit;

use fractalCms\components\Constant;
use fractalCms\helpers\Cms;
use fractalCms\models\ConfigItem;
use fractalCms\models\ConfigType;
use fractalCms\models\Content;
use fractalCms\models\Item;
use fractalCms\models\Menu;
use fractalCms\models\MenuItem;
use fractalCms\models\Parameter;
use fractalCms\models\Slug;
use fractalCms\models\Tag;
use fractalCms\models\User;
use fractalCms\models\Seo;
use fractalCms\Module;
use Tests\Support\UnitTester;
use yii\helpers\Json;
use Yii;

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
        Yii::setAlias('@test', dirname(__DIR__, 1).'/');
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
        //With Child
        $menuItem3 = new MenuItem(['scenario' => MenuItem::SCENARIO_CREATE]);
        $menuItem3->name = 'menuItem 2';
        $menuItem3->route = '/content/index';
        $menuItem3->menuItemId = $menuItem2->id;
        $menuItem3->menuId = $menu->id;
        $this->assertTrue($menuItem3->save());
        $menuItem3->attach();
        $menuItem3->move($menuItem);
        $menuItemQuery = $menu->getMenuItems();
        $structure = $menu->buildStructure($menuItemQuery);
        $this->assertIsArray($structure);
        $menu->move($menuItem, $menuItem3);
        $menu->reorder(true);
        $menu->sourceMenuItemId = $menuItem3->id;
        $menu->destMenuItemId = $menuItem->id;
        $this->assertTrue($menu->moveMenuItem());
        $menu->sourceMenuItemId = null;
        $menu->destMenuItemId = null;
        $this->assertFalse($menu->moveMenuItem());
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

    public function testSlug()
    {
        $slug = new Slug(['scenario' => Slug::SCENARIO_CREATE]);
        $slug->active = 1;
        $slug->path = 'accueil';
        $this->assertFalse($slug->save());
        $slug->path = 'nouveau-test';
        $this->assertTrue($slug->save());
        $slug->refresh();
        $contents = $slug->getContents()->all();
        $this->assertEmpty($contents);
        $content = $slug->getContent()->one();
        $this->assertNull($content);
    }

    public function testConfigItem()
    {
        $configItem = new ConfigItem(['scenario' => ConfigItem::SCENARIO_CREATE]);
        $configItem->name = 'card-article';
        $configItem->config = Json::encode(self::$itemConfigTitle);
        $this->assertFalse($configItem->validate());
        $configItem->name = 'title-test';
        $this->assertTrue($configItem->save());
        $configItem = ConfigItem::find()->where(['name' => 'article'])->one();
        $this->assertNotNull($configItem);
        $items = $configItem->getItems()->all();
        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
    }

    public function testConfigType()
    {
        $config = new ConfigType(['scenario' => ConfigType::SCENARIO_CREATE]);
        $config->name = 'article';
        $config->config = '/content/';
        $this->assertFalse($config->validate());
        $config->name = 'article-test';
        $config->config = '/content/article-test';
        $this->assertTrue($config->save());
        $config = ConfigType::find()->where(['name' => 'article'])->one();
        $this->assertNotNull($config);
        $contents = $config->getContents()->all();
        $this->assertIsArray($contents);
        $this->assertNotEmpty($contents);
    }

    public function testItem()
    {
        $configItem = ConfigItem::find()->where(['name' => 'bandeau-notification'])->one();
        $this->assertNotNull($configItem);
        $item = new Item(['scenario' => Item::SCENARIO_CREATE]);
        $item->active = 1;
        $this->assertFalse($item->validate());
        $item->configItemId = $configItem->id;
        $this->assertTrue($item->save());
        /** @var ConfigItem $config */
        $config = $item->getConfigItem()->one();
        $this->assertNotNull($config);
        $this->assertEquals($configItem->id, $config->id);
        $contents = $item->getContents()->all();
        $this->assertIsArray($contents);
        $this->assertEmpty($contents);



    }

    public function testTag()
    {
        $config = new ConfigType(['scenario' => ConfigType::SCENARIO_CREATE]);
        $config->name = 'tag-test';
        $config->config = '/tag/tag-test';
        $this->assertTrue($config->save());

        $tag = new Tag(['scenario' => Tag::SCENARIO_CREATE]);
        $tag->name = 'Nouveauté';
        $tag->configTypeId = $config->id;
        $this->assertFalse($tag->save());
        $tag->name = 'nouveau-test';
        //Seo
        $seo = new Seo(['scenario' => Seo::SCENARIO_CREATE]);
        $seo->active = 1;
        $seo->title = 'nouveau-test';
        $seo->description = 'nouveau-test';
        $seo->changefreq = 'monthly';
        $this->assertTrue($seo->save());
        $seo->refresh();
        $tag->seoId = $seo->id;

        //SLug
        $slug = new Slug(['scenario' => Slug::SCENARIO_CREATE]);
        $slug->active = 1;
        $slug->path = 'nouveau-test';
        $this->assertTrue($slug->save());
        $slug->refresh();
        $tag->slugId = $slug->id;

        $this->assertTrue($tag->save());
        $tag->refresh();
        //Get slug
        $slug = $tag->getSlug()->one();
        $this->assertNotNull($slug);
        //get SEO
        $seo = $tag->getSeo()->one();
        $this->assertNotNull($seo);
        $configItem = ConfigItem::find()->where(['name' => 'bandeau-notification'])->one();
        $this->assertNotNull($configItem);

        $item = new Item(['scenario' => Item::SCENARIO_CREATE]);
        $item->active = 1;
        $this->assertFalse($item->validate());
        $item->configItemId = $configItem->id;
        $this->assertTrue($item->save());
        $tag->attachItem($item);

        $configItem = ConfigItem::find()->where(['name' => 'hero'])->one();
        $this->assertNotNull($configItem);

        $item2 = new Item(['scenario' => Item::SCENARIO_CREATE]);
        $item2->active = 1;
        $this->assertFalse($item2->validate());
        $item2->configItemId = $configItem->id;
        $this->assertTrue($item2->save());
        $tag->attachItem($item2);
        $items = $tag->getItems()->all();
        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
        $this->assertEquals(2, count($items));
        //Detach item
        $tag->detachItem($item2);
        $items = $tag->getItems()->all();
        $this->assertIsArray($items);
        $this->assertEquals(1, count($items));
        //ReattachItem
        $tag->attachItem($item2);
        $tag->reOrderItems();
        $items = $tag->getItems()->all();
        $this->assertIsArray($items);
        $this->assertEquals(2, count($items));
        //Delete item
        $tag->deleteItem($item2);
        $items = $tag->getItems()->all();
        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
        $this->assertEquals(1, count($items));

        $item = $tag->getItemByConfigId($configItem->id);
        $this->assertNull($item);
        $route = $tag->getRoute();
        $prefix = Module::getInstance()->getContextId();
        $expected =  '/'.$prefix.'/tag-'.$tag->id;
        $this->assertEquals($expected, $route);
        $tagItems = $tag->getTagItems()->all();
        $this->assertIsArray($tagItems);
        $this->assertNotEmpty($tagItems);
        $this->assertEquals(1, count($tagItems));
        $contents = $tag->getContents()->all();
        $this->assertEmpty($contents);
        $contentTags = $tag->getContentTags()->all();
        $this->assertEmpty($contentTags);
        /** @var ConfigType $configType */
        $configType = $tag->getConfigType()->one();
        $this->assertNotNull($configType);
        $this->assertEquals($config->id, $configType->id);
    }

    public function testContent()
    {
        //TAg
        $config = new ConfigType(['scenario' => ConfigType::SCENARIO_CREATE]);
        $config->name = 'tag-test';
        $config->config = '/tag/tag-test';
        $this->assertTrue($config->save());

        $tag = new Tag(['scenario' => Tag::SCENARIO_CREATE]);
        $tag->name = 'Nouveauté';
        $tag->configTypeId = $config->id;
        $this->assertFalse($tag->save());
        $tag->name = 'nouveau-test';
        $this->assertTrue($tag->save());
        //content
        $config = new ConfigType(['scenario' => ConfigType::SCENARIO_CREATE]);
        $config->name = 'article-test';
        $config->config = '/content/article-test';
        $this->assertTrue($config->save());

        $content = new Content(['scenario' => Content::SCENARIO_CREATE]);
        $content->name = 'accueil';
        $content->configTypeId = $config->id;
        $content->type = Content::TYPE_SECTION;
        $content->parentPathKey = '1';
        $this->assertFalse($content->save());
        $content->name = 'article-test';
        $this->assertTrue($content->validate());
        $content->attach();
        $this->assertNotEmpty($content->pathKey);
        $this->assertTrue($content->save());
        //Slug Tag 'nouveaute'
        $slug = new Slug(['scenario' => Slug::SCENARIO_CREATE]);
        $slug->active = 1;
        $slug->path = $slug->validateAndBuild(Slug::cleanPath('contenus'));
        $this->assertTrue($slug->save());
        $slug->refresh();
        $content->slugId = $slug->id;
        //Seo
        $seo = new Seo(['scenario' => Seo::SCENARIO_CREATE]);
        $seo->active = 1;
        $seo->title = 'article-test';
        $seo->description = 'article-test';
        $seo->changefreq = 'monthly';
        $this->assertTrue($seo->save());
        $seo->refresh();
        $content->seoId = $seo->id;
        $content->formTags[] = $tag->id;
        $content->manageTags();
        $this->assertTrue($content->save());
        $content->refresh();
        $target = $slug->getTarget()->one();
        $this->assertNotNull($target);
        $contentFromTag = $tag->getContents()->all();
        $this->assertNotEmpty($contentFromTag);
        $this->assertEquals(1, count($contentFromTag));
        $contentTags = $tag->getContentTags()->all();
        $this->assertNotEmpty($contentTags);
        $this->assertEquals(1, count($contentTags));
        //Item bandeau
        $configItem = ConfigItem::find()->where(['name' => 'bandeau-notification'])->one();
        $this->assertNotNull($configItem);
        $item = new Item(['scenario' => Item::SCENARIO_CREATE]);
        $item->active = 1;
        $this->assertFalse($item->validate());
        $item->configItemId = $configItem->id;
        $this->assertTrue($item->save());
        $content->attachItem($item);
        //Item hero
        $configItem = ConfigItem::find()->where(['name' => 'hero'])->one();
        $this->assertNotNull($configItem);
        $item2 = new Item(['scenario' => Item::SCENARIO_CREATE]);
        $item2->active = 1;
        $this->assertFalse($item2->validate());
        $item2->configItemId = $configItem->id;
        $this->assertTrue($item2->save());
        $content->attachItem($item2);
        $items = $content->getItems()->all();
        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
        $this->assertEquals(2, count($items));
        $content->items[$item2->id] = [
            'title' => 'titre hero',
            'description' => 'description hero'
        ];
        $content->items[$item->id] = [
            'title' => 'titre hero',
            'icon' => '@test/Support/Data/new.svg'
        ];
        $content->manageItems(false);
        $itemDb = Item::findOne($item2->id);
        $title = ($itemDb->hasAttribute('title') === true ) ? $itemDb->title : null;
        $this->assertNotNull($title);
        $this->assertEquals('titre hero', $title);

        $description = ($itemDb->hasAttribute('description') === true ) ? $itemDb->description : null;
        $this->assertNotNull($description);
        $this->assertEquals('description hero', $description);

        $itemDb->title = 'Mon autre titre hero';
        $this->assertTrue($itemDb->save());
        $title = ($itemDb->hasAttribute('title') === true ) ? $itemDb->title : null;
        $this->assertNotNull($title);
        $this->assertEquals('Mon autre titre hero', $title);
        //Icon
        $itemBandeau = Item::findOne($item->id);
        $this->assertNotNull($itemBandeau);
        $icon = ($itemBandeau->hasAttribute('icon') === true ) ? $itemBandeau->icon : null;
        $this->assertNotNull($icon);
        $match = preg_match('/^(@webroot)(.+)$/', $icon) == 1;
        $this->assertTrue($match);




        //first imte
        $firstItem = $content->findFirstItemById($configItem->id);
        $this->assertNotNull($firstItem);
        //Detach item
        $content->detachItem($item2);
        $items = $content->getItems()->all();
        $this->assertIsArray($items);
        $this->assertEquals(1, count($items));
        //ReattachItem
        $content->attachItem($item2);
        $content->reOrderItems();
        $items = $content->getItems()->all();
        $this->assertIsArray($items);
        $this->assertEquals(2, count($items));
        $item2->move($content->id, $item->id);
        $contentItem = $item2->getContentItems()->all();
        $this->assertIsArray($contentItem);
        $this->assertEquals(1, count($contentItem));
        //Delete item
        $content->deleteItem($item2);
        $items = $content->getItems()->all();
        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
        $this->assertEquals(1, count($items));

        $item = $content->getItemByConfigId($configItem->id);
        $this->assertNull($item);
        $route = $content->getRoute();
        $prefix = Module::getInstance()->getContextId();
        $expected =  '/'.$prefix.'/content-'.$content->id;
        $this->assertEquals($expected, $route);
        $contentItems = $content->getContentItems()->all();
        $this->assertIsArray($contentItems);
        $this->assertNotEmpty($contentItems);
        $this->assertEquals(1, count($contentItems));
        /** @var ConfigType $configType */
        $configType = $content->getConfigType()->one();
        $this->assertNotNull($configType);
        $this->assertEquals($config->id, $configType->id);

        $slug = $content->getSlug()->one();
        $this->assertNotNull($slug);
        $seo = $content->getSeo()->one();
        $this->assertNotNull($seo);
        $parents = $content->getParents()->all();
        $this->assertNotEmpty($parents);
        $this->assertEquals(1, count($parents));
        /** @var Content $parent */
        $parent = $content->getParent();
        $this->assertNotNull($seo);
        $this->assertEquals('1',$parent->pathKey);
        $children = $parent->getChildrens()->all();
        $this->assertNotEmpty($children);
        $this->assertTrue($content->isTypeSection());
        $this->assertFalse($content->isTypeArticle());
        $type = $content->displayType();
        $this->assertNotEmpty($type);
        $this->assertEquals('Section', $type);

        $articles = $content->getArticles()->all();
        $this->assertEmpty($articles);
        $level = $content->getLevel();
        $this->assertEquals(2, $level);

        //Delete item
        $content->deleteItem($itemBandeau);
        $items = $content->getItems()->all();
        $this->assertIsArray($items);
        $this->assertEmpty($items);
        $this->assertEquals(0, count($items));

    }
}
