<?php

namespace App\Tests\Service;

use App\Entity\League;
use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Service\LeagueService;
use App\Service\TeamService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamServiceTest extends MockeryTestCase
{
    /**
     * @var TeamService
     */
    private $service;

    /**
     * @var MockInterface
     */
    private $teamRepository;

    /**
     * @var MockInterface
     */
    private $leagueService;

    public function setUp()
    {
        $this->teamRepository = Mockery::mock(TeamRepository::class);
        $this->leagueService  = Mockery::mock(LeagueService::class);
        $this->service        = new TeamService($this->teamRepository, $this->leagueService);
    }

    public function testCreatesTeam()
    {

        $league = Mockery::mock(League::class);
        $league->shouldReceive('addTeam')
            ->once();

        $this->leagueService->shouldReceive('getLeagueById')
            ->once()
            ->with(1)
            ->andReturn($league);

        $this->teamRepository->shouldReceive('findOneBy')
            ->once()
            ->andReturn(null);

        $this->teamRepository->shouldReceive('save')
            ->once();

        $team = $this->service->create('foo', 'bar', 1);
        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals('foo', $team->getName());
        $this->assertEquals('bar', $team->getStrip());
    }

    public function testCreateThrowsConflictHttpException()
    {
        $league = Mockery::mock(League::class);

        $this->leagueService->shouldReceive('getLeagueById')
            ->once()
            ->with(1)
            ->andReturn($league);

        $this->teamRepository->shouldReceive('findOneBy')
            ->once()
            ->andReturn(Mockery::mock(Team::class));

        $this->expectException(ConflictHttpException::class);

        $team = $this->service->create('foo', 'bar', 1);
    }

    public function testThrowsHttpException()
    {
        $league = Mockery::mock(League::class);
        $league->shouldReceive('addTeam')
            ->once();

        $this->leagueService->shouldReceive('getLeagueById')
            ->once()
            ->with(1)
            ->andReturn($league);

        $this->teamRepository->shouldReceive('findOneBy')
            ->once()
            ->andReturn(null);

        $this->teamRepository->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception());

        $this->expectException(HttpException::class);
        $this->service->create('foo', 'bar', 1);
    }

    public function testUpdates()
    {
        $team = Mockery::mock(Team::class);

        $this->teamRepository->shouldReceive('find')
            ->once()
            ->andReturn($team);

        $team->shouldReceive('setName')->once();
        $team->shouldReceive('setStrip')->once();

        $this->teamRepository->shouldReceive('save')
            ->once();

        $this->service->update('1', 'foo', 'bar');
    }

    public function testUpdateThrowsNotFoundException()
    {

        $this->teamRepository->shouldReceive('find')
            ->once()
            ->andReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->service->update('1', 'foo', 'bar');
    }

    public function testUpdateThrowsHttpException()
    {
        $team = Mockery::mock(Team::class);

        $this->teamRepository->shouldReceive('find')
            ->once()
            ->andReturn($team);

        $team->shouldReceive('setName')->once();
        $team->shouldReceive('setStrip')->once();

        $this->teamRepository->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception());

        $this->expectException(HttpException::class);

        $this->service->update('1', 'foo', 'bar');
    }
}
