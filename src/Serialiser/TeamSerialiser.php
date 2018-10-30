<?php declare(strict_types=1);

namespace App\Serialiser;

use App\Entity\Team;
use Doctrine\Common\Collections\Collection;

class TeamSerialiser
{
    /**
     * @param Team $team
     * @return string
     */
    public function toJson(Team $team): string
    {
        return json_encode($this->toArray($team));
    }

    /**
     * @param Collection $teamCollection
     * @return string
     */
    public function collectionToJson(Collection $teamCollection): string
    {
        $array = [];

        foreach ($teamCollection as $team) {
            $array[] = $this->toArray($team);
        }

        return json_encode($array);
    }

    /**
     * @param Team $team
     * @return array
     */
    private function toArray(Team $team): array
    {
        return [
            'id'    => $team->getId(),
            'name'  => $team->getName(),
            'strip' => $team->getStrip(),
        ];
    }
}
