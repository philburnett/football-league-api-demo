<?php declare(strict_types=1);

namespace App\Controller;

use App\Serialiser\TeamSerialiser;
use App\Service\LeagueService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LeagueController extends Controller
{
    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @var TeamSerialiser
     */
    private $teamSerialiser;

    /**
     * LeagueController constructor.
     *
     * @param LeagueService  $leagueService
     * @param TeamSerialiser $teamSerialiser
     */
    public function __construct(
        LeagueService $leagueService,
        TeamSerialiser $teamSerialiser
    ) {
        $this->leagueService  = $leagueService;
        $this->teamSerialiser = $teamSerialiser;
    }

    /**
     * @Route("/leagues/{id}/teams", methods={"GET"})
     * @IsGranted("ROLE_USER")
     *
     * @param $id
     * @return Response
     */
    public function getLeagueTeams($id)
    {
        $league = $this->leagueService->getLeagueById((int) $id);

        return new Response(
            $this->teamSerialiser->collectionToJson($league->getTeams()),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/leagues/{id}", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     *
     * @param $id
     * @return Response
     */
    public function delete($id)
    {
        $this->leagueService->delete((int) $id);

        return new Response(
            null,
            Response::HTTP_NO_CONTENT,
            ['content-type' => 'application/json']
        );
    }
}
