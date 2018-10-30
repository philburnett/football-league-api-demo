<?php declare(strict_types=1);

namespace App\Tests\ControllerIntegration;

use App\DataFixtures\LeagueFixture;
use App\Tests\DatabasePrimer;
use App\Tests\FrameworkIntegrationTestCase;
use App\Tests\IntegrationTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LeagueControllerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $leagueFixture = new LeagueFixture();
        $leagueFixture->load($this->entityManager);

        $this->fixtureExecutor->execute([$leagueFixture]);
    }

    public function testGetLeagueTeams()
    {
        $this->client->request(
            'GET',
            '/leagues/2/teams',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.' .
                    'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImlhdCI6MTUxNjI' .
                    'zOTAyMn0._O7zDzGeK1PAhyWTU2t6XIZmMbQ0F-7ukg-6ysZp7ig',
            ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(20, count(json_decode($this->client->getResponse()->getContent())));
    }

    public function testDeletesLeague()
    {
        $this->client->request(
            'DELETE',
            '/leagues/2',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.' .
                    'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImlhdCI6MTUxNjI' .
                    'zOTAyMn0._O7zDzGeK1PAhyWTU2t6XIZmMbQ0F-7ukg-6ysZp7ig',
            ]
        );

        $this->expectException(NotFoundHttpException::class);

        $this->client->request(
            'GET',
            '/leagues/2/teams',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.' .
                    'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImlhdCI6MTUxNjI' .
                    'zOTAyMn0._O7zDzGeK1PAhyWTU2t6XIZmMbQ0F-7ukg-6ysZp7ig',
            ]
        );
    }
}
