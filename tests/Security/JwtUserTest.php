<?php

namespace App\Tests\Security;

use App\Security\JwtUser;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class JwtUserTest extends MockeryTestCase
{
    /**
     * @var JwtUser
     */
    private $jwtUser;

    public function setUp()
    {
        $this->jwtUser = new JwtUser('TestName');
        $this->jwtUser->setRoles(['ROLE_TEST']);
    }

    public function testGetUsername()
    {
        $this->assertEquals('TestName', $this->jwtUser->getUsername());
    }

    public function testGetRoles()
    {
        $this->assertEquals(['ROLE_TEST'], $this->jwtUser->getRoles());
    }
}
