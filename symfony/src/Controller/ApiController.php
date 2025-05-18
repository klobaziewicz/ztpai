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
use App\Entity\LikePost;
use App\Service\NotificationSender;
//phpinfo();
//var_dump(bcadd('1', '2'));
#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    private UserRepository $userRepository;
    private PostRepository $postRepository;
    private NotificationSender $notificationSender;
    private EntityManagerInterface $em;
    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        NotificationSender $notificationSender,
        EntityManagerInterface $em
    ) {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->notificationSender = $notificationSender;
        $this->em = $em;
    }

    #[Route('/users', name: 'users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

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

    #[Route('/home', name: 'home', methods: ['GET'])]
    public function home(): JsonResponse
    {
        //$this->denyAccessUnlessGranted('ROLE_USER');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->json([
            'dane' => 'dane dla admina',
        ]);
    }

    #[Route('/user/{nick}', name: 'get_user_by_nick', methods: ['GET'])]
    public function getUserByNick(string $nick): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
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
        $this->denyAccessUnlessGranted('ROLE_USER');
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

    #[Route('/likePost', name: 'likePost', methods: ['POST'])]
    public function likePost(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $data = json_decode($request->getContent(), true);
        $postId = $data['post_id'] ?? null;

        if (!$postId) {
            return $this->json(['error' => 'Brak post_id'], 400);
        }

        $post = $this->postRepository->find($postId);
        $user = $this->getUser();

        if (!$post) {
            return $this->json(['error' => 'Post nie istnieje'], 404);
        }

        $likePost = new LikePost();
        $likePost->setPost($post);
        $likePost->setUser($user);
        $this->em->persist($likePost);
        $this->em->flush();

        $message = json_encode([
            'type' => 'likePost',
            'from_user' => $user->getNick(),
            'to_user' => $post->getUser()->getNick(),
            'post_id' => $post->getId(),
            'timestamp' => time(),
        ]);

        $this->notificationSender->send($message);

        return $this->json(['success' => true, 'message' => 'Polubiono i wysłano powiadomienie']);
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
