<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientType;
use App\Repository\IngredientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IngredientController extends AbstractController
{
    /**
     * fonction pour afficher tous les ingrédients et faire la pagination
     *
     * @param IngredientsRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient')]
    public function index(IngredientsRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $repo->findAll();
        $ingredients = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * création d'ingrédient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = new Ingredients();
        $form = $this->createForm(IngredientType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Ingrédient bien enregistré'
            );
            return $this->redirectToRoute('ingredient');
        }

        return $this->render('ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * 
     */
    #[Route('/ingredient/edit/{id}', name: 'edit', methods: (['GET', 'POST']))]
    // public function edit(Ingredient $ingredient,Request $request, EntityManagerInterface $manager)
    public function edit(IngredientsRepository $repo, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = $repo->findOneBy(["id" => $id]);
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Ingrédient bien modifié'
            );
            return $this->redirectToRoute('ingredient');
        }
        return $this->render('ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

   /**
    * 
    */
    #[Route('/ingredient/delete/{id}', name: 'delete', methods: (['GET']))]
    // public function edit(Ingredient $ingredient, EntityManagerInterface $manager)
    public function delete(IngredientsRepository $repo,EntityManagerInterface $manager, int $id): Response
    {
        $ingredient = $repo->findOneBy(["id" => $id]);
        
        if (!$ingredient) {
            $this->addFlash(
                'success',
                'Ingrédient non trouvé !!!'
            );
            return $this->redirectToRoute('ingredient');
        }

        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Ingrédient bien supprimé'
        );
        return $this->redirectToRoute('ingredient');
    }
}
