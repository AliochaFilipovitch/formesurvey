<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\SurveyRepository;
use App\Repository\QuestionRepository;
use App\Entity\Survey;
use App\Entity\Question;
use App\Entity\Answer;
use App\Form\SurveyType;
use App\Form\QuestionType;
use App\Form\AnswerType;


class FormController extends AbstractController
{
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
     * @Route("/{id}", name="answer")
     */
    public function answer(QuestionRepository $question, Survey $survey, Request $request, EntityManagerInterface $manager)
    {

        dump($request);
        //echo $request->request->count();
        //echo $survey->getId();
        $questions = $question->findBy(
            ['survey' => $survey->getId()]
        );
        dump($questions);
        echo count($questions);


 

        if ($request->request->count() > 0) {

            $i=-1;

            foreach ($questions as $question) {
                # code...
                    $i++;

                    //echo $i;

                    $answer = new Answer();
                    $answer->setAnswer($request->request->get("answer$i"))
                           ->setQuestion($question)
                           ->setCreatedAt(new \DateTime());

                    $manager->persist($answer);
                    $manager->flush();


            }
        }

        return $this->render('form/answer.html.twig', [
            'survey' => $survey
        ]);
    }

    /**
     * @Route("/form/new", name="form_create")
     * @Route("/form/{id}/edit", name="form_edit")
     */

    public function form(Survey $survey = null , Request $request, EntityManagerInterface $manager)
    {	
    	if (!$survey) {
    		$survey = new Survey();
    	}
    	
    	$form = $this->createForm(SurveyType::class, $survey); 

    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		if (!$survey->getId()) {
    			$survey->setCreatedAt(new \DateTime());
    		}
    		
    		$manager->persist($survey);
    		$manager->flush();

    		return $this->redirectToRoute('form_survey', ['id'=>$survey->getId()]);

    	}

    	return $this->render('form/create.html.twig', [
    		'surveyForm' => $form->createView(),
    		'editMode' => $survey->getId() !== null
    	]);
    }

    /**
     * @Route("/form/{id}", name="form_survey")
     */

    public function survey(Survey $survey, Request $request, EntityManagerInterface $manager)
    {
    	$question = new Question();
    	
    	$form = $this->createForm(QuestionType::class, $question);

    	$form->handleRequest($request); 

    	if ($form->isSubmitted() && $form->isValid()) {
    		$question->setCreatedAt(new \DateTime())
    				 ->setSurvey($survey);

    		$manager->persist($question);
    		$manager->flush();

    		return $this->redirectToRoute('form_survey', ['id'=>$survey->getId()]);

    	}

    	return $this->render('form/survey.html.twig', [
    		'questionForm' => $form->createView(),
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
