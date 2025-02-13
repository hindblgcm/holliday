<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
    
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'test@test.com']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2', 'Welcome to Hollyday');
        $this->assertSelectorTextContains('p', 'Plan, explore, and cherish your perfect vacation moments with Hollyday.');
    }
    public function testLeaveRequest(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'test@test.com']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit',[
            'leave_request[startDate]' => '2025-02-14T14:52',
            'leave_request[endDate]' => '2025-02-22T14:52',
            'leave_request[reason]' => 'Vacation',
        ]);

        static::assertResponseRedirects();
        $client->followRedirect();
    }
}
