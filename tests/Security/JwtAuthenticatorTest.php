<?php

namespace App\Tests\Security;

use App\Security\JwtAuthenticator;
use App\Security\JwtUser;
use App\Security\JwtUserProvider;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtAuthenticatorTest extends MockeryTestCase
{
    /**
     * @var JwtAuthenticator
     */
    private $authenticator;

    private $jwt = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.' .
    'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImlhdCI6MTUxNjIzOTAyMn0.' .
    '_O7zDzGeK1PAhyWTU2t6XIZmMbQ0F-7ukg-6ysZp7ig';

    public function setUp()
    {
        $this->authenticator = new JwtAuthenticator();
    }

    public function testStartReturns401()
    {
        $request  = Mockery::mock(Request::class);
        $response = $this->authenticator->start($request);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testSupportsReturnsTrueWithAuthorizationHeader()
    {
        $request   = Mockery::mock(Request::class);
        $headerBag = Mockery::mock(HeaderBag::class);
        $headerBag->shouldReceive('get')->andReturn($this->jwt);
        $request->headers = $headerBag;

        $this->assertTrue($this->authenticator->supports($request));
    }

    public function testSupportsReturnsFalseWithoutAuthorizationHeader()
    {
        $request   = Mockery::mock(Request::class);
        $headerBag = Mockery::mock(HeaderBag::class);
        $headerBag->shouldReceive('get')->andThrow(new \Exception());
        $request->headers = $headerBag;

        $this->assertFalse($this->authenticator->supports($request));
    }

    public function testGetCredentials()
    {
        $request   = Mockery::mock(Request::class);
        $headerBag = Mockery::mock(HeaderBag::class);
        $headerBag->shouldReceive('get')->andReturn($this->jwt);
        $request->headers = $headerBag;

        $creds = $this->authenticator->getCredentials($request);
        $this->assertArrayHasKey('username', $creds);
        $this->assertArrayHasKey('roles', $creds);

        $this->assertEquals('John Doe', $creds['username']);
        $this->assertEquals(['ROLE_USER'], $creds['roles']);
    }

    public function testGetUser()
    {
        $userProvider = new JwtUserProvider();
        $user         = $this->authenticator->getUser(
            ['username' => 'test', 'roles' => ['ROLE_USER']],
            $userProvider
        );

        $this->assertInstanceOf(JwtUser::class, $user);
    }

    public function testCheckCredentialsReturnsTrue()
    {
        $this->assertTrue($this->authenticator->checkCredentials(
            [
                'username' => 'test',
                'roles'    => ['ROLE_USER'],
            ],
            Mockery::mock(UserInterface::class)
        ));
    }

    public function testCheckCredentialsReturnsFalse()
    {
        $this->assertFalse($this->authenticator->checkCredentials(
            [
                'username' => 'test',
            ],
            Mockery::mock(UserInterface::class)
        ));

        $this->assertFalse($this->authenticator->checkCredentials(
            [
                'roles'    => ['ROLE_USER'],
            ],
            Mockery::mock(UserInterface::class)
        ));
    }
}
