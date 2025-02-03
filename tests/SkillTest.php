<?php

namespace App\Tests;

use App\Entity\Skill;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SkillTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testCreateSkill(): void
    {
        $skill = new Skill();
        $skill->setName('PHP');

        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        $this->assertNotNull($skill->getId());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}