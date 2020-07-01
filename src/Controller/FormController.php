<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Survey;
use App\Repository\SurveyRepository;


class FormController extends AbstractController
{
    /**
     * @Route("/form", name="form")
     */
    public function index(SurveyRepository $repo)
    {
    	$surveys = $repo->findAll();
        return $this->render('form/index.html.twig', [
            'controller_name' => 'FormController',
            'surveys' => $surveys
        ]);
    }

    /**
     * @Route("/", name="home")
     */

    public function home()
    {
    	return $this->render('form/home.html.twig', [
    		'title' => "Bienvenue les amis!"
    	]);
    }

    /**
     * @Route("/form/{id}", name="form_survey")
     */

    public function survey(Survey $survey)
    {
    	return $this->render('form/survey.html.twig', [
    		'survey' => $survey
    	]);
    }

    /**
     * @Route("/result/{id}", name="form_result")
     */

    public function result(Survey $survey)
    {
    	return $this->render('form/result.html.twig', [
    		'survey' => $survey
    	]);
    }
}
