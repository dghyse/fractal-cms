<?php


namespace Tests\Unit;

use fractalCms\models\User;
use Tests\Support\UnitTester;

class ModelTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testSomeFeature()
    {
        $user = new User();
        $user->scenario = User::SCENARIO_CREATE;
        $user->email = 'text.fr';
        $user->password = 'text';
        $validate = $user->validate();
        $this->assertFalse($validate);
    }
}
