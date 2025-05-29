<?php

namespace App\Tests\Controller;

use App\Entity\UserList;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerIntegrationTest extends WebTestCase
{
    public function testGetUsersReturnsListOfUsers(): void
    {
        $client = static::createClient();
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $entityManager->createQuery('DELETE FROM App\Entity\Post')->execute();
        $entityManager->createQuery('DELETE FROM App\Entity\UserList')->execute();

        // Tworzymy użytkowników w bazie danych (poprzez EntityManager)
        $user1 = new UserList();
        $user1->setName('John Doe')
            ->setNick('johndoe')
            ->setEmail('john@example.com')
            ->setPassword('password123');

        $user2 = new UserList();
        $user2->setName('Jane Doe')
            ->setNick('janedoe')
            ->setEmail('jane@example.com')
            ->setPassword('password123');

        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user1);
        $entityManager->persist($user2);
        $entityManager->flush();

        // Wykonujemy zapytanie GET
        $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();

        // Sprawdzamy, czy odpowiedź zawiera dwóch użytkowników
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(2, $data);
        $this->assertSame('johndoe', $data[0]['nick']);
        $this->assertSame('janedoe', $data[1]['nick']);
    }

    public function testGetUserByNickReturnsUserData(): void
    {
        $client = static::createClient();

        // Tworzymy użytkownika w bazie danych
        $user = new UserList();
        $user->setName('John Doe')
            ->setNick('johndoe')
            ->setEmail('john@example.com')
            ->setPassword('password123');

        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Wykonujemy zapytanie GET
        $client->request('GET', '/api/user/johndoe');
        $this->assertResponseIsSuccessful();

        // Sprawdzamy, czy odpowiedź zawiera dane użytkownika
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('john@example.com', $data['email']);
        $this->assertSame('johndoe', $data['nick']);
    }
    public function testGetUserByNickNotFound(): void
    {
        $client = static::createClient();

        // Wykonujemy zapytanie GET z nieistniejącym nickiem
        $client->request('GET', '/api/user/unknown');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetPostsReturnsListOfPosts(): void
    {
        $client = static::createClient();

        // Tworzymy użytkownika i posty w bazie danych
        $user = new UserList();
        $user->setName('John Doe')
            ->setNick('johndoe')
            ->setEmail('john@example.com')
            ->setPassword('password123');

        $post1 = new Post();
        $post1->setContent('This is post 1')
            ->setUser($user);

        $post2 = new Post();
        $post2->setContent('This is post 2')
            ->setUser($user);

        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->persist($post1);
        $entityManager->persist($post2);
        $entityManager->flush();

        // Wykonujemy zapytanie GET
        $client->request('GET', '/api/posts');
        $this->assertResponseIsSuccessful();

        // Sprawdzamy, czy odpowiedź zawiera posty
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(2, $data);
        $this->assertSame('This is post 1', $data[0]['content']);
        $this->assertSame('This is post 2', $data[1]['content']);
    }

    public function testGetPostByIdReturnsPostData(): void
    {
        $client = static::createClient();

        // Tworzymy użytkownika i post w bazie danych
        $user = new UserList();
        $user->setName('John Doe')
            ->setNick('johndoe')
            ->setEmail('john@example.com')
            ->setPassword('password123');

        $post = new Post();
        $post->setContent('This is a single post')
            ->setUser($user);

        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->persist($post);
        $entityManager->flush();

        // Wykonujemy zapytanie GET
        $client->request('GET', '/api/post/' . $post->getId());
        $this->assertResponseIsSuccessful();

        // Sprawdzamy, czy odpowiedź zawiera dane posta
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('This is a single post', $data['content']);
    }

    public function testGetPostByIdNotFound(): void
    {
        $client = static::createClient();

        // Wykonujemy zapytanie GET z nieistniejącym id
        $client->request('GET', '/api/post/999');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCreateUserSuccess(): void
    {
        $client = static::createClient();

        // Wykonujemy zapytanie POST
        $client->request(
            'POST',
            '/api/createUser',
            [], // brak parametrów w URL
            [], // brak plików
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
            'name' => 'New User',
            'nick' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password123'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($client->getResponse()->getContent());
    }
}
