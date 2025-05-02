<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    public function testGetUserByNickReturnsUserData(): void
    {
        $client = static::createClient();

        // Mock repozytorium
        $user = (new User())
            ->setName('John Doe')
            ->setNick('johndoe')
            ->setEmail('john@example.com')
            ->setPassword('secret'); // Hasło nieistotne w tym teście

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findOneBy')
            ->with(['nick' => 'johndoe'])
            ->willReturn($user);

        self::getContainer()->set(UserRepository::class, $userRepository);

        $client->request('GET', '/api/user/johndoe');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame('John Doe', $responseData['name']);
        $this->assertSame('johndoe', $responseData['nick']);
        $this->assertSame('john@example.com', $responseData['email']);
    }

    public function testGetUserByNickReturns404IfUserNotFound(): void
    {
        $client = static::createClient();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findOneBy')
            ->with(['nick' => 'unknown'])
            ->willReturn(null);

        self::getContainer()->set(UserRepository::class, $userRepository);

        $client->request('GET', '/api/user/unknown');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
