<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dish;
use App\Entity\Category;
use App\Entity\Allergen;
use App\Entity\User;
use App\Form\DishFormType;
use App\Form\UserFormType;
use App\Form\AllergenFormType;
use App\Form\CategoryFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DishRepository;
use App\Repository\AllergenRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;

class AdminController extends AbstractController
{
   

   /**
    * @Route("/import-dishes", name="admin_importDishes", methods={"GET", "HEAD"})
    */
    public function importDishesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $json = file_get_contents('..\public\dishes.json');
        $data = json_decode($json, true);
        $dishRepo = $em->getRepository(Dish::class);
        $categoryRepo = $em->getRepository(Category::class);
        $allergenRepo = $em->getRepository(Allergen::class);
        $userRepo = $em->getRepository(User::class);
        foreach (["desserts", "entrees", "plats"] as $type) {
            $category = $categoryRepo->findOneBy(array("name"=> ucfirst( $type ) ));
            if ($category === null) {
                $category = new AppCategory();
                $category->setName($type);
                $category->setImage("https://media.istockphoto.com/photos/eating-restaurant-dish-on-white-plate-on-black-background-picture-id896863874");
                $em->persist($category);
            }
            
            if ($category && isset($data[$type])) {
                foreach ($data[$type] as $dishArray) {
                    $dish = $dishRepo->findOneBy(
                        array("name"=>$dishArray["name"])
                        );
                    if ($dish === null) {
                        $dish = new Dish(); // Insert
                    }
                   
                    $users = $userRepo->findOneBy([]);
                  
                    $dish->setName($dishArray["name"]);
                    $dish->setUser($users);
                    $dish->setCalories($dishArray["calories"]);
                    $dish->setPrice((float)$dishArray["price"]);
                    $dish->setDescription($dishArray["text"]);
                    $dish->setSticky($dishArray["sticky"]);
                    $dish->setImage($dishArray["image"]);
                    $dish->setCategory($category);
                    foreach ($dishArray["allergens"] as $allergenArray) {
                        $a = $allergenRepo->findOneBy(["name" => $allergenArray]);
                        if ($a === null) {
                            $a = new Allergen();
                            $a->setName($allergenArray);
                        }
                        
                        $a->addDish($dish);
                        $em->persist($a);
                        $dish->addAllergen($a);
                        
                    }
                    
                    $em->persist($dish);
                    $em->flush();
                    
                }
            }
            
        }
        return $this->render('admin/insertDish.twig', [
            'controller_name' => 'AdminController',
        ]);

    }

     /**
     * @Route("/admin/dish/new", name="new_dish", methods={"GET","POST"})
     */
    public function addDish(Request $request): Response
    {
        $dish = new Dish();
        $form = $this->createForm(DishFormType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dish);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Le plat  '.$form["name"]->getData().' a bien été créé'
            );
            return $this->redirectToRoute("list_dish");
        }
        return $this->render("admin/dish-form.html.twig", [
            "form_title" => "Ajouter un plat",
            "form_dish" => $form->createView(),
        ]);
    }

    /**
    * @Route("/admin/dish", name="list_dish")
    */
    public function dish()
    {
        $dishes = $this->getDoctrine()->getRepository(Dish::class)->findAll();

        return $this->render('admin/dish.html.twig', [
            "dishes" => $dishes,
        ]);
    }

    /**
    * @Route("/admin/dish/{id}/show", name="dish_detail")
    */
    public function dishDetail(DishRepository $dishRep, int $id): Response
    {
        $dish = $this->getDoctrine()->getRepository(Dish::class)->find($id);

        foreach( $dishRep->findAll() as $dish){
            if($dish->getId() == $id){
                return $this->render("admin/dish_detail.html.twig", [
                    "dish" => $dish,
                ]);
            }
        }

        
    }


    /**
    * @Route("/admin/dish/edit/{id}", name="modify_dish")
    */
    public function modifyProduct(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $dish = $entityManager->getRepository(Dish::class)->find($id);
        $form = $this->createForm(DishFormType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'Le plat  '.$form["name"]->getData().' a bien été modifié'
            );
            return $this->redirectToRoute("list_dish");
        }

        return $this->render("admin/dish-form.html.twig", [
            "form_title" => "Modifier un plat",
            "form_dish" => $form->createView(),
        ]);
    }

    /**
    * @Route("admin/dish/delete/{id}", name="delete_dish")
    */
    public function deleteDish(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dish = $entityManager->getRepository(Dish::class)->find($id);
        $entityManager->remove($dish);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Le plat a bien '.$dish->getName().' été supprimé'
        );

        return $this->redirectToRoute("list_dish");
    }

    /**
    * @Route("/admin/user", name="list_user")
    */
    public function user()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/user.html.twig', [
            "users" => $users,
        ]);
    }

      /**
    * @Route("/admin/user/{id}/show", name="user_detail")
    */
    public function userDetail(UserRepository $userRep, int $id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        foreach( $userRep->findAll() as $user){
            if($user->getId() == $id){
                return $this->render("admin/user_detail.html.twig", [
                    "user" => $user,
                ]);
            }
        }

        
    }

     /**
     * @Route("/admin/user/new", name="new_user", methods={"GET","POST"})
     */
    public function addUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'L utilisateur  '.$form["username"]->getData().' a bien été créé'
            );
            return $this->redirectToRoute("list_user");
        }
        return $this->render("admin/user-form.html.twig", [
            "form_title" => "Ajouter un utilisateur",
            "form_user" => $form->createView(),
        ]);
    }

     /**
    * @Route("/admin/user/edit/{id}", name="modify_user")
    */
    public function modifyUser(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'L utilisateur  '.$form["username"]->getData().' a bien été modifié'
            );
            return $this->redirectToRoute("list_user");
        }

        return $this->render("admin/user-form.html.twig", [
            "form_title" => "Modifier un utilisateur",
            "form_user" => $form->createView(),
        ]);
    }

    /**
    * @Route("admin/user/delete/{id}", name="delete_user")
    */
    public function deleteUser(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'L utilisateur '.$user->getUsername().' a bien été supprimé'
        );

        return $this->redirectToRoute("list_user");
    }


    /**
    * @Route("/admin/allergen", name="list_allergen")
    */
    public function allergen()
    {
        $allergens = $this->getDoctrine()->getRepository(Allergen::class)->findAll();

        return $this->render('admin/allergen.html.twig', [
            "allergens" => $allergens,
        ]);
    }

     /**
    * @Route("/admin/allergen/{id}/show", name="allergen_detail")
    */
    public function allergenDetail(AllergenRepository $allergenRep, int $id): Response
    {
        $allergen = $this->getDoctrine()->getRepository(Allergen::class)->find($id);

        foreach( $allergenRep->findAll() as $allergen){
            if($allergen->getId() == $id){
                return $this->render("admin/allergen_detail.html.twig", [
                    "allergen" => $allergen,
                ]);
            }
        }

        
    }

     /**
     * @Route("/admin/allergen/new", name="new_allergen", methods={"GET","POST"})
     */
    public function addAllergen(Request $request): Response
    {
        $allergen = new Allergen();
        $form = $this->createForm(AllergenFormType::class, $allergen);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($allergen);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'L allergène  '.$form["name"]->getData().' a bien été créé'
            );
            return $this->redirectToRoute("list_allergen");
        }
        return $this->render("admin/allergen-form.html.twig", [
            "form_title" => "Ajouter un allergene",
            "form_allergen" => $form->createView(),
        ]);
    }

     /**
    * @Route("/admin/allergen/edit/{id}", name="modify_allergen")
    */
    public function modifyAllergen(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $allergen = $entityManager->getRepository(Allergen::class)->find($id);
        $form = $this->createForm(AllergenFormType::class, $allergen);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'L allergene  '.$form["name"]->getData().' a bien été modifié'
            );
            return $this->redirectToRoute("list_allergen");
        }

        return $this->render("admin/allergen-form.html.twig", [
            "form_title" => "Modifier un allergene",
            "form_allergen" => $form->createView(),
        ]);
    }

    /**
    * @Route("admin/allergen/delete/{id}", name="delete_allergen")
    */
    public function deleteAllergen(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $allergen = $entityManager->getRepository(Allergen::class)->find($id);
        $entityManager->remove($allergen);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'L allergenne '.$allergen->getName().' a bien été supprimé'
        );

        return $this->redirectToRoute("list_allergen");
    }


    /**
    * @Route("/admin/category", name="list_category")
    */
    public function category()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('admin/category.html.twig', [
            "categories" => $categories,
        ]);
    }

 
    /**
    * @Route("/admin/category/{id}/show", name="category_detail")
    */
    public function categoryDetail(CategoryRepository $categoryRep, int $id): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        foreach( $categoryRep->findAll() as $category){
            if($category->getId() == $id){
                return $this->render("admin/category_detail.html.twig", [
                    "category" => $category,
                ]);
            }
        }

        
    }

     /**
     * @Route("/admin/category/new", name="new_category", methods={"GET","POST"})
     */
    public function addCategory(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'La categorie  '.$form["name"]->getData().' a bien été créé'
            );
            return $this->redirectToRoute("list_category");
        }
        return $this->render("admin/category-form.html.twig", [
            "form_title" => "Ajouter une categorie",
            "form_category" => $form->createView(),
        ]);
    }

     /**
    * @Route("/admin/category/edit/{id}", name="modify_category")
    */
    public function modifyCategory(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $category = $entityManager->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'La categorie  '.$form["name"]->getData().' a bien été modifié'
            );
            return $this->redirectToRoute("list_category");
        }

        return $this->render("admin/category-form.html.twig", [
            "form_title" => "Modifier un categorie",
            "form_category" => $form->createView(),
        ]);
    }

    /**
    * @Route("admin/category/delete/{id}", name="delete_category")
    */
    public function deleteCategory(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'La categorie '.$category->getName().' a bien été supprimée'
        );

        return $this->redirectToRoute("list_category");
    }

    /**
    * @Route("/admin", name="gerer")
    */
    public function gerer()
    {
       

        return $this->render('admin.html.twig');
    }


}
