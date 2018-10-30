<?php

namespace App\Tests\Security;

use App\Security\JwtUser;
use App\Security\JwtUserProvider;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;

class JwtUserProviderTest extends MockeryTestCase
{
    /**
     * @var JwtUserProvider
     */
    private $jwtUserProvider;

    public function setUp()
    {
        $this->jwtUserProvider = new JwtUserProvider();
    }

    public function testLoadUserByUsername()
    {
        $user = $this->jwtUserProvider->loadUserByUsername('test');
        $this->assertInstanceOf(JwtUser::class, $user);
        $this->assertEquals('test', $user->getUsername());
    }

    public function testSupportsClassReturnsTrue()
    {
        $this->assertTrue($this->jwtUserProvider->supportsClass(JwtUser::class));
    }

    public function testSupportsClassReturnsFalse()
    {
        $this->assertFalse($this->jwtUserProvider->supportsClass(User::class));
    }
}
