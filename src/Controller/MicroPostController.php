<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(EntityManagerInterface $entityManager): Response
    {

       // dd($entityManager->getRepository(MicroPost::class)->findAll());

        // $microPost = new MicroPost();
        // $microPost->setTitle('Welcome to the first post from conrtoller');
        // $microPost->setText('This is the text of the first post from controller');
        // $microPost->setCreated(new DateTime());
        // tell Doctrine you want to (eventually) save the Product (no queries yet);
        //$repository = $entityManager->getRepository(MicroPost::class);
        //$microPost = $repository->find(5);
//
        //if (!$microPost) {
        //    throw $this->createNotFoundException(
        //        'No product found for id 4'
        //    );
        //}
//
        //$entityManager->remove($microPost);
        ////$microPost->setTitle('changed!');
//
//
        //$entityManager->persist($microPost);
//
        //// actually executes the queries (i.e. the INSERT query)
        //$entityManager->flush();

        return $this->render('micro_post/index.html.twig', [
            'posts' => $entityManager->getRepository(MicroPost::class)->findAll(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        //dd($microPostRepository->find($id));
        // dd($post);

        //$microPost = $microPostRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$post
            );
        }
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, PersistenceManagerRegistry $doctrine): Response
    {

        $microPost = new MicroPost();
        $form = $this->createForm(MicroPostType::class, $microPost);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $microPost->setCreated(new DateTime());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($microPost);
            $entityManager->flush();

            // Add flash
            $this->addFlash('success', 'Post was created!');


           return $this->redirectToRoute('app_micro_post');
        }

        return $this->render('micro_post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    public function edit(MicroPost $post, Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            // Add flash
            $this->addFlash('success', 'Your mirco post have been updated!');


            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render('micro_post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
