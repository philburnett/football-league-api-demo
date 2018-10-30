<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\League;
use App\Repository\LeagueRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LeagueService
{
    /**
     * @var LeagueRepository
     */
    private $leagueRepository;

    /**
     * TeamService constructor.
     *
     * @param LeagueRepository $leagueRepository
     */
    public function __construct(LeagueRepository $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    /**
     * @param int $id
     * @return League
     */
    public function getLeagueById(int $id): League
    {
        $league = $this->leagueRepository->find($id);

        if (is_null($league)) {
            throw new NotFoundHttpException('Cannot find league');
        }

        return $league;
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $league = $this->leagueRepository->find($id);

        if (is_null($league)) {
            throw new NotFoundHttpException('Could not find league ' . $id);
        }

        try {
            $this->leagueRepository->delete($league);
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}
