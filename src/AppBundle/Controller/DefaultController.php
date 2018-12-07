<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->where('p.createdAt <= CURRENT_DATE()')
            ->from('AppBundle:Post', 'p')
            ->select('p');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->get('page', 1),
            20,
            array('defaultSortFieldName' => 'p.createdAt', 'defaultSortDirection' => 'desc')
        );

        return $this->render('default/index.html.twig', [
            'posts' => $pagination
        ]);
    }

    /**
     * @Route("/article/{id}", name="post_show")
     */
    public function showAction(Post $post, Request $request)
    {
        $form = null;
        $comment = new Comment();
        $comment->setPost($post);

        if($user = $this->getUser()) {
            $comment->setUser($user);

            $form = $this->createFormBuilder($comment)
                ->add('content', TextareaType::class, array(
                    'label' => false,
                    'attr' => array('placeholder' => 'Treść komentarza')
                ))
                ->getForm();

            $form->handleRequest($request);
            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', 'Komentarz został zapisany!');
                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }
        }

        return $this->render('default/show.html.twig', [
            'post' => $post,
            'form' => is_null($form) ? $form : $form->createView()
        ]);
    }
}
