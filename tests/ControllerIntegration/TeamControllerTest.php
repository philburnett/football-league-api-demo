<?php declare(strict_types=1);

namespace App\Tests\ControllerIntegration;

use App\DataFixtures\LeagueFixture;
use App\Entity\Team;
use App\Tests\IntegrationTestCase;

class TeamControllerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $leagueFixture = new LeagueFixture();
        $leagueFixture->load($this->entityManager);

        $this->fixtureExecutor->execute([$leagueFixture]);
    }

    public function testCreatesANewTeam()
    {
        $teams = $this->getTeams();

        $this->assertEquals(20, count($teams));

        $this->client->request(
            'POST',
            '/teams',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => $_ENV['TEST_JWT'],
            ],
            json_encode(['leagueId' => 2, 'name' => 'testy tester', 'strip' => 'foobar'])
        );
        $this->getTeams();

        $this->assertEquals(21, count(json_decode($this->client->getResponse()->getContent())));
    }

    public function testUpdatesAnExistingTeam()
    {
        $teams = $this->getTeams();

        /** @var Team $updateTeam */
        $updateTeam = array_pop($teams);

        $this->client->request(
            'PUT',
            '/teams/' . $updateTeam['id'],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.' .
                    'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImlhdCI6MTUxNjI' .
                    'zOTAyMn0._O7zDzGeK1PAhyWTU2t6XIZmMbQ0F-7ukg-6ysZp7ig',
            ],
            json_encode(['name' => 'Updated Name', 'strip' => 'New Strip'])
        );

        $teams = $this->getTeams();

        foreach ($teams as $team) {
            if ($team['id'] === $updateTeam['id']) {
                $this->assertEquals('Updated Name', $team['name']);
                $this->assertEquals('New Strip', $team['strip']);
            }
        }
    }

    private function getTeams()
    {
        $this->client->request(
            'GET',
            '/leagues/2/teams',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => $_ENV['TEST_JWT'],
            ]
        );

        return json_decode($this->client->getResponse()->getContent(), true);
    }
}
