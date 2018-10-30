<?php declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IntegrationTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ORMExecutor
     */
    protected $fixtureExecutor;

    public function setUp()
    {
        parent::setUp();

        self::$kernel = self::createKernel();
        self::bootKernel();

        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException(
                'Primer must be executed in the test environment (' . self::$kernel->getEnvironment() . ')'
            );
        }

        // Get the entity manager from the service container
        $this->entityManager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        // Drop and recreate tables for all entities
        $metadata  = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $this->fixtureExecutor = new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager));

        $this->client = self::createClient();
    }
}
