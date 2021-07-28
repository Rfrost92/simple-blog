<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * @Route("post/{slug}", name="blog_post", methods={"GET", "POST"})
     */
    public function show(BlogPost $blogPost): Response
    {
        return $this->render('blog/show.html.twig', [
            'post' => $blogPost,
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $blogList = $this->getDoctrine()->getManager()->getRepository(BlogPost::class)->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'blogPosts' => $blogList,
        ]);
    }
}
