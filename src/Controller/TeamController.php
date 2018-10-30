<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\TeamService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TeamController extends Controller
{
    /**
     * @var TeamService
     */
    private $teamService;

    /**
     * TeamController constructor.
     *
     * @param TeamService $teamService
     */
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * @Route("/teams", methods={"POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $team = $this->teamService->create($body['name'], $body['strip'], $body['leagueId']);

        return new Response(
            json_encode(['href' => "/teams/" . $team->getId()]),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/teams/{id}", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param         $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $body = json_decode($request->getContent(), true);
        $this->teamService->update((int) $id, $body['name'], $body['strip']);

        return new Response(
            null,
            Response::HTTP_NO_CONTENT,
            ['content-type' => 'application/json']
        );
    }
}
