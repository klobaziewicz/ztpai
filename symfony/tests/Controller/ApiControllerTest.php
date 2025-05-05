<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    public function testGetUsersReturnsUserList(): void
    {
        $client = static::createClient();

        $user = (new User())
            ->setName('Test User')
            ->setNick('testuser')
            ->setEmail('test@example.com')
            ->setPassword('pass');

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findAll')->willReturn([$user]);

        self::getContainer()->set(UserRepository::class, $repo);

        $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(1, $data);
        $this->assertSame('testuser', $data[0]['nick']);
    }

    public function testGetUserByNickSuccess(): void
    {
        $client = static::createClient();

        $user = (new User())
            ->setName('Nick Name')
            ->setNick('nick')
            ->setEmail('nick@example.com')
            ->setPassword('secret');

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findOneBy')->willReturn($user);

        self::getContainer()->set(UserRepository::class, $repo);

        $client->request('GET', '/api/user/nick');
        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('nick@example.com', $data['email']);
    }

    public function testGetUserByNickNotFound(): void
    {
        $client = static::createClient();

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findOneBy')->willReturn(null);

        self::getContainer()->set(UserRepository::class, $repo);

        $client->request('GET', '/api/user/missing');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetUserByIdSuccess(): void
    {
        $client = static::createClient();

        $user = (new User())
            ->setName('ID User')
            ->setNick('iduser')
            ->setEmail('id@example.com')
            ->setPassword('secret');

        $repo = $this->createMock(UserRepository::class);
        $repo->method('find')->willReturn($user);

        self::getContainer()->set(UserRepository::class, $repo);

        $client->request('GET', '/api/userId/1');
        $this->assertResponseIsSuccessful();
    }

    public function testGetUserByIdNotFound(): void
    {
        $client = static::createClient();

        $repo = $this->createMock(UserRepository::class);
        $repo->method('find')->willReturn(null);

        self::getContainer()->set(UserRepository::class, $repo);

        $client->request('GET', '/api/userId/999');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetPostsReturnsPosts(): void
    {
        $client = static::createClient();

        // Tworzymy użytkownika
        $user = (new User())
            ->setId(1)
            ->setName('Test User')
            ->setNick('testnick')
            ->setEmail('test@example.com')
            ->setPassword('password');

        // Tworzymy post i przypisujemy użytkownika
        $post = new Post();
        $post->setId(1)
            ->setContent('Example post')
            ->setUser($user); // Przypisujemy obiekt User, a nie string

        // Tworzymy mock repozytorium
        $repo = $this->createMock(PostRepository::class);
        $repo->method('findAll')->willReturn([$post]);

        self::getContainer()->set(PostRepository::class, $repo);

        $client->request('GET', '/api/posts');
        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(1, $data);
    }


    public function testGetPostByIdSuccess(): void
    {
        $user = (new User())
            ->setId(1)
            ->setName('Test User')
            ->setNick('tester')
            ->setEmail('tester@example.com')
            ->setPassword('123456');

        $post = (new Post())
            ->setId(1)
            ->setUser($user)
            ->setContent('Test post content');

        $postRepository = $this->createMock(PostRepository::class);
        $postRepository->method('find')->with(1)->willReturn($post);

        $client = static::createClient();
        self::getContainer()->set(PostRepository::class, $postRepository);

        $client->request('GET', '/api/post/1');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame(1, $data['id']);
        $this->assertSame('Test post content', $data['content']);
    }

    public function testGetPostByIdNotFound(): void
    {
        $client = static::createClient();

        $repo = $this->createMock(PostRepository::class);
        $repo->method('find')->willReturn(null);

        self::getContainer()->set(PostRepository::class, $repo);

        $client->request('GET', '/api/post/999');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCreateUserSuccess(): void
    {
        $client = static::createClient();

        $repo = $this->createMock(UserRepository::class);
        $repo->expects($this->once())->method('save');

        self::getContainer()->set(UserRepository::class, $repo);

        $client->request(
            'POST',
            '/api/createUser',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Nowy',
                'nick' => 'newbie',
                'email' => 'nowy@example.com',
                'password' => 'haslo123'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testCreateUserInvalidJson(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/createUser',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'not-a-json'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserMissingFields(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/createUser',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['nick' => 'incomplete']) // Brakuje name, email, password
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
