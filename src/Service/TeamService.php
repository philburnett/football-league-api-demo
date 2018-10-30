<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamService
{
    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * TeamService constructor.
     *
     * @param TeamRepository $teamRepository
     * @param LeagueService  $leagueService
     */
    public function __construct(
        TeamRepository $teamRepository,
        LeagueService $leagueService
    ) {
        $this->leagueService  = $leagueService;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param string $name
     * @param string $strip
     * @param        $leagueId
     * @return Team
     */
    public function create(string $name, string $strip, $leagueId): Team
    {
        $league = $this->leagueService->getLeagueById($leagueId);

        $team = $this->teamRepository->findOneBy([
            'name' => $name,
        ]);

        if (!is_null($team)) {
            throw new ConflictHttpException('Team already exists');
        }

        try {
            $team = new Team($name, $strip);
            $league->addTeam($team);
            $this->teamRepository->save($team);
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }

        return $team;
    }

    /**
     * @param int    $id
     * @param string $name
     * @param string $strip
     * @return Team
     */
    public function update(int $id, string $name, string $strip): Team
    {
        $team = $this->teamRepository->find($id);

        if (is_null($team)) {
            throw new NotFoundHttpException('Cannot find team ' . $id);
        }

        try {
            $team->setName($name);
            $team->setStrip($strip);
            $this->teamRepository->save($team);
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }

        return $team;
    }
}
