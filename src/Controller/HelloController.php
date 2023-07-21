<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello World!', 'created_at' => '2023-06-01'],
        ['message' => 'Hello Symfony!', 'created_at' => '2023-05-02'],
        ['message' => 'Hello PHP!', 'created_at' => '2022-01-03'],
    ];

    #[Route('/{limit<\d+>?3}', name: 'app_index')]
    public function index(int $limit): Response
    {
        return $this->render('hello/index.html.twig', [
            'messages' => $this->messages,
            'limit' => $limit,
        ]);
    }

    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne(int $id): Response
    {
        return $this->render('hello/show_one.html.twig', [
            'message' => $this->messages[$id] ?? 'No message found with id ' . $id,
        ]);
    }
}