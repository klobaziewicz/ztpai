<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

    #[Route('/posts/{id}', name: 'get_post_by_id', methods: ['GET'])]
    public function getPostById(int $id): JsonResponse
    {
        $posts = [
            1 => ['id' => 1, 'name' => 'Jan Kowalski', 'content' =>
                'post 1'],
            2 => ['id' => 2, 'name' => 'Anna Nowak', 'content' =>
                'post 2'],
        ];
        if (!isset($posts[$id])) {
            return $this->json(['error' => 'User not found'], 404);
        }
        return $this->json($posts[$id]);
    }
}
