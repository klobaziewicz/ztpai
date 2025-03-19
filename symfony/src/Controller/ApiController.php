<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/home', name: 'home', methods: ['GET'])]
    public function home(): JsonResponse
    {
        return new JsonResponse(['message' => 'Welcome to Symfony API']);
    }

    #[Route('/users', name: 'users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        try {
            $users = [
                ['id' => 1, 'name' => 'Jan Kowalski', 'email' =>
                    'jan@example.com'],
                ['id' => 2, 'name' => 'Anna Nowak', 'email' =>
                    'anna@example.com'],
            ];
            return $this->json($users);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/user/{nick}', name: 'get_user_by_nick', methods: ['GET'])]
    public function getUserByNick(string $nick): JsonResponse
    {
        $users = [
            1 => ['id' => 1, 'nick'=>'jkowalski','name' => 'Jan Kowalski', 'email' =>
                'jan@example.com'],
            2 => ['id' => 2, 'nick'=>'anowak', 'name' => 'Anna Nowak', 'email' =>
                'anna@example.com'],
        ];
        foreach ($users as $user) {
            if ($user['nick'] === $nick) {
                return $this->json($user);
            }
        }
        return $this->json($users[$nick]);
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
