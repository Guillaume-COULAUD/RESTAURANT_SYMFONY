<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;

class FrontController extends AbstractController
{

     /**
    * @Route("/", name="homepage")
    */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }


    /**
    * @Route("/equipe", methods={"GET"}, name="front_team")
    */
    public function afficherEquipe(UserRepository $userRep): Response
    {
        return $this->render('front/afficherEquipe.html.twig', [
            'users' => $userRep->findAll() ,
        ]);
    }

     /**
    * @Route("/admin/", methods={"GET"}, name="admin_home")
    */
    public function back(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
    * @Route("/carte", methods={"GET"}, name="front_dishes")
    */
    public function afficherCategory(CategoryRepository $categoryRep): Response
    {
        
        return $this->render('front/afficherCategory.html.twig', [
            'categories' => $categoryRep->findAll() ,
        ]);
    }

    /**
    * @Route("/carte/{index}", methods={"GET"}, requirements={"index"="\d+"}, name="front_dishes__category")
    */
    public function afficherDetailsCategory(CategoryRepository $categoryRep, int $index): Response
    {
        foreach( $categoryRep->findAll() as $category){
            if($category->getId() == $index){
                return $this->render('front/detailCategory.html.twig', [
                    'category' => $category ,
                ]);
            }
        }
       
    }
  
}
