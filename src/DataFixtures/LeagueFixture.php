<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\League;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LeagueFixture extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $leagues = [
            'Premiership' => [
                'Arsenal'                 => 'Red & white',
                'Bournemouth'             => 'Red & black',
                'Brighton & Hove Albion'  => 'Blue & white',
                'Burnley'                 => 'Red & blue',
                'Cardiff City'            => 'Blue',
                'Chelsea'                 => 'Blue',
                'Crystal Palace'          => 'Red & blue',
                'Everton'                 => 'Blue & white',
                'Fulham'                  => 'White & black',
                'Huddersfield Town'       => 'Blue & white',
                'Leicester City'          => 'Blue & white',
                'Liverpool'               => 'Red',
                'Manchester City'         => 'Blue',
                'Manchester United'       => 'Red',
                'Newcastle United'        => 'Black & white',
                'Southampton'             => 'Red',
                'Tottenham Hotspur'       => 'White',
                'Watford'                 => 'Yellow & black',
                'West Ham United'         => 'Red & blue',
                'Wolverhampton Wanderers' => 'Orange',
            ],
        ];

        foreach ($leagues as $name => $teams) {
            $league = new League($name);
            foreach ($teams as $teamName => $strip) {
                $newTeam = new Team($teamName, $strip);
                $league->addTeam($newTeam);
            }
            $manager->persist($league);
        }
        $manager->flush();
    }
}
