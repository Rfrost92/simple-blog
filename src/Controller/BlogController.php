<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\BlogPost;
use App\Entity\User;
use App\Form\Type\BlogPostType;
use App\Utils\Slugifier;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * @Route("post/new", name="new_post")
     */
    public function new(Request $request): Response
    {
        // creates a task object and initializes some data for this example
        $post = new BlogPost();
        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('Europe/Berlin'));
        $post->setUpdated($dateTime);

        $form = $this->createForm(BlogPostType::class, $post);
        $email = $this->getUser()->getUsername();
        $author = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email' => $email]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $blogPost = new BlogPost();
            $blogPost->setTitle($data->getTitle());
            $blogPost->setBody($data->getBody());
            $blogPost->setAuthor($author);
            $blogPost->setSlug(Slugifier::slugify($data->getTitle()));
            $blogPost->setUpdated($dateTime);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'blog/new.html.twig',
            [
                'blogPostForm' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("post/edit/{slug}", name="edit_post", methods={"GET", "POST"})
     */
    public function edit(Request $request, BlogPost $post)
    {
        $form = $this->createForm(BlogPostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $post->setTitle($data->getTitle());
            $post->setBody($data->getBody());

            $dateTime = new \DateTime();
            $dateTime->setTimezone(new \DateTimeZone('Europe/Berlin'));
            $post->setUpdated($dateTime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'blog/edit.html.twig',
            [
                'blogPostForm' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("post/{slug}", name="blog_post", methods={"GET", "POST"})
     */
    public function show(BlogPost $blogPost): Response
    {
        return $this->render(
            'blog/show.html.twig',
            [
                'post' => $blogPost,
            ]
        );
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $blogList = $this->getDoctrine()->getManager()->getRepository(BlogPost::class)->findAllOrderedByTime();
        return $this->render(
            'blog/index.html.twig',
            [
                'controller_name' => 'BlogController',
                'blogPosts' => $blogList,
            ]
        );
    }

}
