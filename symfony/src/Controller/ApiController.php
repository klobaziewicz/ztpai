<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    private UserRepository $userRepository;
    private PostRepository $postRepository;
    public function __construct(UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }
    #[Route('/home', name: 'home', methods: ['GET'])]
    public function home(): JsonResponse
    {
        return new JsonResponse(['message' => 'Welcome to Symfony API']);
    }

    #[Route('/users', name: 'users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        try {
            // Pobieranie użytkowników z bazy danych
            $users = $this->userRepository->findAll();

            // Mapowanie wyników na format JSON
            $userData = [];
            foreach ($users as $user) {
                $userData[] = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'nick' => $user->getNick(),
                    'email' => $user->getEmail(),
                ];
            }

            return $this->json($userData);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/user/{nick}', name: 'get_user_by_nick', methods: ['GET'])]
    public function getUserByNick(string $nick): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['nick' => $nick]);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'nick' => $user->getNick(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/userId/{id}', name: 'get_user_by_id', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'nick' => $user->getNick(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/posts', name: 'get_posts', methods: ['GET'])]
    public function getPosts(): JsonResponse
    {
        try {
            $posts = $this->postRepository->findAll();
            $postData = [];
            foreach ($posts as $post) {
                $postData[] = [
                    'id' => $post->getId(),
                    'user' => $post->getUser(),
                    'content' => $post->getContent(),
                    'createdAt' => $post->getCreatedAt(),
                ];
            }

            return $this->json($postData);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/post/{id}', name: 'get_post_by_id', methods: ['GET'])]
    public function getPostById(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json([
           'id' => $post->getId(),
           'user' => $post->getUser(),
           'content' => $post->getContent(),
           'createdAt' => $post->getCreatedAt(),
        ]);
    }
    #[Route('/createUser', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            unset($data['id']);
            $name = $data['name'] ?? null;
            $nick = $data['nick'] ?? null;
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            if (!$name || !$nick || !$email || !$password) {
                return $this->json(['error' => 'Missing required fields'], 400);
            }

            $user = new User();
            $user->setName($name)
                ->setNick($nick)
                ->setEmail($email)
                ->setPassword($password);

            $this->userRepository->save($user, true);

            return $this->json(['message' => 'User created successfully'], 201);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
