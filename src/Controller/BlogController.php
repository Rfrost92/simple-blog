<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

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
