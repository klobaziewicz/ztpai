<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

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

    #[Route('/users', name: 'users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $this->userRepository->findAll();

        $userData = array_map(function (User $user) {
            return [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'nick' => $user->getNick(),
                'email' => $user->getEmail(),
            ];
        }, $users);

        return $this->json($userData);
    }

    #[Route('/user/{nick}', name: 'get_user_by_nick', methods: ['GET'])]
    public function getUserByNick(string $nick): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['nick' => $nick]);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
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
            throw new NotFoundHttpException('User not found');
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
        $posts = $this->postRepository->findAll();

        $postData = array_map(function ($post) {
            return [
                'id' => $post->getId(),
                'user' => $post->getUser(),
                'content' => $post->getContent(),
                'createdAt' => $post->getCreatedAt(),
            ];
        }, $posts);

        return $this->json($postData);
    }

    #[Route('/post/{id}', name: 'get_post_by_id', methods: ['GET'])]
    public function getPostById(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            throw new NotFoundHttpException('Post not found');
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
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            throw new BadRequestHttpException('Invalid JSON');
        }

        unset($data['id']);
        $name = $data['name'] ?? null;
        $nick = $data['nick'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$name || !$nick || !$email || !$password) {
            throw new BadRequestHttpException('Missing required fields');
        }

        $user = new User();
        $user->setName($name)
            ->setNick($nick)
            ->setEmail($email)
            ->setPassword($password);

        $this->userRepository->save($user, true);

        return $this->json(['message' => 'User created successfully'], 201);
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['email'], $data['password'], $data['name'], $data['nick'])) {
            throw new BadRequestHttpException('Missing required fields');
        }
        $user = new User();
        $user->setName($data['name']);
        $user->setNick($data['nick']);
        $user->setEmail($data['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['status' => 'User registered'], Response::HTTP_CREATED);
    }
//    #[Route('/login', name: 'login', methods: ['POST'])]
//    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
//    {
//        $data = json_decode($request->getContent(), true);
//        if (!isset($data['email'], $data['password'], $data['name'], $data['nick'])) {
//            throw new BadRequestHttpException('Missing required fields');
//        }
//        $user = new User();
//        $user->setName($data['name']);
//        $user->setNick($data['nick']);
//        $user->setEmail($data['email']);
//        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
//        $user->setRoles(['ROLE_USER']);
//        $em->persist($user);
//        $em->flush();
//
//        return new JsonResponse(['status' => 'User registered'], Response::HTTP_CREATED);
//    }
}
