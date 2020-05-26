<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="wild_show")
     * @return Response
     */
    public function show($slug): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()->getRepository(Program::class)->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException('No program with' . $slug . 'title, found in program\'s table.');
        }
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @param string $categoryName
     * @Route("wild/category/{categoryName}", name="show_category").
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name' => mb_strtolower($categoryName)]);
        $programs = $this->getDoctrine()->getRepository(Program::class)->findBy(['category' => $category],['id'=> "DESC"], 3);
        return $this->render('wild/category.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }
}
