<?php

namespace App\Tests\Service;

use App\Entity\League;
use App\Repository\LeagueRepository;
use App\Service\LeagueService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LeagueServiceTest extends MockeryTestCase
{
    /**
     * @var LeagueService
     */
    private $service;

    /**
     * @var MockInterface
     */
    private $leagueRepository;

    public function setUp()
    {
        $this->leagueRepository = Mockery::mock(LeagueRepository::class);
        $this->service          = new LeagueService($this->leagueRepository);
    }

    public function testGetLeagueById()
    {
        $league = Mockery::mock(League::class);
        $this->leagueRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($league);

        $league = $this->service->getLeagueById(1);

        $this->assertInstanceOf(League::class, $league);
    }

    public function testThrowsNotFoundException()
    {
        $this->leagueRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->service->getLeagueById(1);
    }

    public function testDeletesLeague()
    {
        $league = Mockery::mock(League::class);
        $this->leagueRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($league);

        $this->leagueRepository->shouldReceive('delete')
            ->once()
            ->with($league);

        $this->service->delete(1);
    }

    public function testDeleteThrowsNotFoundException()
    {
        $this->leagueRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->service->delete(1);
    }

    public function testDeleteThrowsHttpException()
    {
        $league = Mockery::mock(League::class);
        $this->leagueRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($league);

        $this->leagueRepository->shouldReceive('delete')
            ->once()
            ->with($league)
            ->andThrow(new \Exception());

        $this->expectException(HttpException::class);
        $this->service->delete(1);
    }
}
