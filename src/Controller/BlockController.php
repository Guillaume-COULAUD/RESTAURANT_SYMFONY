<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Dish;

class BlockController extends AbstractController
{
    #[Route('/block', name: 'block')]
    public function index(): Response
    {
        return $this->render('block/index.html.twig', [
            'controller_name' => 'BlockController',
        ]);
    }


    public function  DayDishes($max = 3): Response
    {
        $em=$this->getDoctrine()->getRepository(Category::class);
        $category = $em->findOneBy(['id'=>1]);
        $dishes = $this->getDoctrine()->getRepository(Dish::class)->findStickies($category,$max);
            return $this->render(
            'Partials/day_dishes.html.twig',
            array('dishes' => $dishes)
        );

    }
}
