<?php

namespace App\Tests\Repository;

use App\Entity\Tag;
use App\Entity\TagType;
use App\Entity\TimeSystem;
use App\Repository\TimeSystemRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TimeSystemRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager = null;
    private ?TimeSystemRepository $repository = null;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->entityManager->beginTransaction();
        $this->repository = $this->entityManager->getRepository(TimeSystem::class);

        $this->setupTestData();
    }

    protected function tearDown(): void
    {
        if ($this->entityManager) {
            $this->entityManager->rollback();
            $this->entityManager->close();
            $this->entityManager = null;
        }
        parent::tearDown();
    }

    private function setupTestData(): void
    {
        // 1. Create TagTypes
        $organType = new TagType();
        $organType->setName('Organ');
        $this->entityManager->persist($organType);

        $elementType = new TagType();
        $elementType->setName('Element');
        $this->entityManager->persist($elementType);

        // 2. Create Tags
        $spleenTag = new Tag();
        $spleenTag->setName('Spleen');
        $spleenTag->setTagType($organType);
        $this->entityManager->persist($spleenTag);

        $gallbladderTag = new Tag();
        $gallbladderTag->setName('Gallbladder');
        $gallbladderTag->setTagType($organType);
        $this->entityManager->persist($gallbladderTag);

        $woodTag = new Tag();
        $woodTag->setName('Wood');
        $woodTag->setTagType($elementType);
        $this->entityManager->persist($woodTag);

        // 3. Create TimeSystems
        // Standard block: 09:00:00 to 11:00:00
        $standardBlock = new TimeSystem();
        $standardBlock->setName('Spleen Block');
        $standardBlock->setFromTime(new \DateTime('09:00:00'));
        $standardBlock->setToTime(new \DateTime('11:00:00'));
        $standardBlock->addTag($spleenTag);
        $this->entityManager->persist($standardBlock);

        // Overnight block: 23:00:00 to 01:00:00
        $overnightBlock = new TimeSystem();
        $overnightBlock->setName('Gallbladder Block');
        $overnightBlock->setFromTime(new \DateTime('23:00:00'));
        $overnightBlock->setToTime(new \DateTime('01:00:00'));
        $overnightBlock->addTag($gallbladderTag);
        $overnightBlock->addTag($woodTag);
        $this->entityManager->persist($overnightBlock);

        $this->entityManager->flush();
    }

    public function testGetActiveForTimeStandardBlock(): void
    {
        $blocks = $this->repository->getActiveForTime('10:00:00');
        $this->assertCount(1, $blocks);
        $this->assertSame('Spleen Block', $blocks[0]->getName());
    }

    public function testGetActiveForTimeStandardBounds(): void
    {
        // Lower bound
        $blocksLower = $this->repository->getActiveForTime('09:00:00');
        $this->assertCount(1, $blocksLower);
        $this->assertSame('Spleen Block', $blocksLower[0]->getName());

        // Upper bound
        $blocksUpper = $this->repository->getActiveForTime('11:00:00');
        $this->assertCount(1, $blocksUpper);
        $this->assertSame('Spleen Block', $blocksUpper[0]->getName());
    }

    public function testGetActiveForTimeOvernightBlock(): void
    {
        // Query within Gallbladder block before midnight
        $blocksBefore = $this->repository->getActiveForTime('23:30:00');
        $this->assertCount(1, $blocksBefore);
        $this->assertSame('Gallbladder Block', $blocksBefore[0]->getName());

        // Query within Gallbladder block after midnight
        $blocksAfter = $this->repository->getActiveForTime('00:30:00');
        $this->assertCount(1, $blocksAfter);
        $this->assertSame('Gallbladder Block', $blocksAfter[0]->getName());
    }

    public function testGetActiveForTimeOvernightBounds(): void
    {
        // Lower bound
        $blocksLower = $this->repository->getActiveForTime('23:00:00');
        $this->assertCount(1, $blocksLower);
        $this->assertSame('Gallbladder Block', $blocksLower[0]->getName());

        // Upper bound
        $blocksUpper = $this->repository->getActiveForTime('01:00:00');
        $this->assertCount(1, $blocksUpper);
        $this->assertSame('Gallbladder Block', $blocksUpper[0]->getName());
    }

    public function testGetActiveForTimeEmpty(): void
    {
        $blocks = $this->repository->getActiveForTime('12:00:00');
        $this->assertEmpty($blocks);
    }

    public function testGetActiveForTimeEagerLoading(): void
    {
        $this->entityManager->clear();
        $blocks = $this->repository->getActiveForTime('10:00:00');
        $this->assertNotEmpty($blocks);

        // Detach all entities to test eager loading
        $this->entityManager->clear();

        $block = $blocks[0];
        $tags = $block->getTags();
        $this->assertNotEmpty($tags);
        foreach ($tags as $tag) {
            $this->assertNotNull($tag->getName());
            $this->assertNotNull($tag->getTagType()->getName());
        }
    }
}
