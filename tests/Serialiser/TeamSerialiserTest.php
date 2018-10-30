<?php

namespace App\Tests\Serialiser;

use App\Entity\Team;
use App\Serialiser\TeamSerialiser;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\TestCase;

class TeamSerialiserTest extends MockeryTestCase
{
    /**
     * @var TeamSerialiser
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TeamSerialiser();
    }

    public function testSerializes()
    {
        $team = new Team('foo', 'bar');
        $team->setId(1);

        $string = $this->serializer->toJson($team);

        $this->assertEquals(
            '{"id":1,"name":"foo","strip":"bar"}',
            $string
        );
    }

    public function testSerializesCollection()
    {
        $team = new Team('foo', 'bar');
        $team->setId(1);

        $team2 = new Team('badgers', 'mash potato');
        $team2->setId(2);

        $string = $this->serializer->collectionToJson(new ArrayCollection([$team, $team2]));

        $this->assertEquals(
            '[{"id":1,"name":"foo","strip":"bar"},{"id":2,"name":"badgers","strip":"mash potato"}]',
            $string
        );
    }
}
