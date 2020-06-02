<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("category/add", name="add_category").
     * @return Response
     */
    public function add(Request $request)
    {
        $category = new Category();
        $category->setName('');

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


