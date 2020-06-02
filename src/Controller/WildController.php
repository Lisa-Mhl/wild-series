<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\CategoryType;

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
    public function showByProgram($slug): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()->getRepository(Program::class)->findOneBy(['title' => mb_strtolower($slug)]);
        $seasons = $this->getDoctrine()->getRepository(Season::class)->findBy(['program' => $program]);

        if (!$program) {
            throw $this->createNotFoundException('No program with' . $slug . 'title, found in program\'s table.');
        }
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
            'seasons' => $seasons,
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
        $programs = $this->getDoctrine()->getRepository(Program::class)->findBy(['category' => $category], ['id' => "DESC"], 3);
        return $this->render('wild/category.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }

    /**
     * @param Season $season
     * @Route("/wild/season/{id}", name="show_season")
     * @return Response
     */
    public function showBySeason(Season $season): Response
    {
        $episodes = $season->getEpisodes();
        $program = $season->getProgram();
        return $this->render('wild/season.html.twig', [
            'season' => $season,
            'program' => $program,
            'episodes' => $episodes,
        ]);
    }

    /**
     * @Route("/wild/episode/{id}", name="show_episode")
     * @param Episode $episode
     * @return Response
     */

    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        return $this->render('wild/episode.html.twig', [
                'program' => $program,
                'episode' => $episode,
                'season' => $season,
            ]
        );
    }

}
