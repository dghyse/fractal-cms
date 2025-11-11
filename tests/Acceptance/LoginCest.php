<?php

declare(strict_types=1);


namespace Tests\Acceptance;

use fractalCms\Module;
use Tests\Support\AcceptanceTester;

final class LoginCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function tryToTest(AcceptanceTester $I): void
    {
        $prefix = Module::getInstance()->id;
        $I->amOnPage('/'.$prefix.'/authentification/login');
        $I->seeResponseCodeIs(200);
        $I->see('Veuillez vous identifier');
    }
}
