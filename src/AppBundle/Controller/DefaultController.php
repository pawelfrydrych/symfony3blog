<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->from('AppBundle:Post', 'p')
            ->select('p');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->get('page', 1),
            20
        );

        return $this->render('default/index.html.twig', [
            'posts' => $pagination
        ]);
    }

    /**
     * @Route("/article/{id}", name="post_show")
     */
    public function showAction(Post $post)
    {
        return $this->render('default/show.html.twig', [
            'post' => $post
        ]);
    }
}
