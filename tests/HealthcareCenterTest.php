<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthcareCenterTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/healthcarecenter/cabinet-medical-des-acacias');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('.logo', 'Cabinet Médical des Acacias');
    }
}