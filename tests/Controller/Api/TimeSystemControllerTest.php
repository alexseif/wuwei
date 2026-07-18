<?php

namespace App\Tests\Controller\Api;

use App\Entity\Tag;
use App\Entity\TagType;
use App\Entity\TimeSystem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TimeSystemControllerTest extends WebTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function setupTestData($client): void
    {
        $this->entityManager = $client->getContainer()->get('doctrine')->getManager();
        $this->entityManager->beginTransaction();

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
        $standardBlock = new TimeSystem();
        $standardBlock->setName('Spleen Block');
        $standardBlock->setFromTime(new \DateTime('09:00:00'));
        $standardBlock->setToTime(new \DateTime('11:00:00'));
        $standardBlock->addTag($spleenTag);
        $this->entityManager->persist($standardBlock);

        $overnightBlock = new TimeSystem();
        $overnightBlock->setName('Gallbladder Block');
        $overnightBlock->setFromTime(new \DateTime('23:00:00'));
        $overnightBlock->setToTime(new \DateTime('01:00:00'));
        $overnightBlock->addTag($gallbladderTag);
        $overnightBlock->addTag($woodTag);
        $this->entityManager->persist($overnightBlock);

        $this->entityManager->flush();
    }

    private function rollbackTestData(): void
    {
        if ($this->entityManager && $this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }
    }

    public function testGetTemporalBlocksNoParams(): void
    {
        $client = static::createClient();
        $this->setupTestData($client);

        try {
            $client->request('GET', '/api/v1/temporal-blocks');
            $this->assertResponseIsSuccessful();
            
            $responseContent = $client->getResponse()->getContent();
            $data = json_decode($responseContent, true);
            $this->assertIsArray($data);
        } finally {
            $this->rollbackTestData();
        }
    }

    public function testGetTemporalBlocksValidTime(): void
    {
        $client = static::createClient();
        $this->setupTestData($client);

        try {
            $client->request('GET', '/api/v1/temporal-blocks', ['time' => '10:00']);
            $this->assertResponseIsSuccessful();

            $responseContent = $client->getResponse()->getContent();
            $data = json_decode($responseContent, true);
            
            $this->assertCount(1, $data);
            $this->assertSame('Spleen Block', $data[0]['name']);
            $this->assertSame('09:00:00', $data[0]['from_time']);
            $this->assertSame('11:00:00', $data[0]['to_time']);
            
            $this->assertArrayHasKey('tags', $data[0]);
            $this->assertSame(['Spleen'], $data[0]['tags']['Organ']);
        } finally {
            $this->rollbackTestData();
        }
    }

    public function testGetTemporalBlocksOvernightTime(): void
    {
        $client = static::createClient();
        $this->setupTestData($client);

        try {
            $client->request('GET', '/api/v1/temporal-blocks', ['time' => '23:30:00']);
            $this->assertResponseIsSuccessful();

            $responseContent = $client->getResponse()->getContent();
            $data = json_decode($responseContent, true);

            $this->assertCount(1, $data);
            $this->assertSame('Gallbladder Block', $data[0]['name']);
            
            $tags = $data[0]['tags'];
            $this->assertSame(['Gallbladder'], $tags['Organ']);
            $this->assertSame(['Wood'], $tags['Element']);
        } finally {
            $this->rollbackTestData();
        }
    }

    public function testGetTemporalBlocksInvalidTimeFormat(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/temporal-blocks', ['time' => 'invalid-time']);
        
        $this->assertSame(400, $client->getResponse()->getStatusCode());
        
        $responseContent = $client->getResponse()->getContent();
        $data = json_decode($responseContent, true);
        
        $this->assertArrayHasKey('error', $data);
        $this->assertStringContainsString('Invalid time format', $data['error']);
    }
}
