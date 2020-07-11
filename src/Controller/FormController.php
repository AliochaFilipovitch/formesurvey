<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\SurveyRepository;
use App\Repository\QuestionRepository;
use App\Entity\Survey;
use App\Entity\Question;
use App\Entity\Answer;
use App\Form\SurveyType;
use App\Form\QuestionType;
// use App\Form\AnswerType;


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
     * @Route("/form/{id}/status", name="status")
     */
    public function status(SurveyRepository $repo, Survey $survey = null, EntityManagerInterface $manager) : Response
    {   

        if (!$survey) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

        $survey = $repo->findOneBy(['id' => $survey->getId()]);

        if ($survey->getStatus(true)) {
            $survey->setStatus(false);
        } else {
            $survey->setStatus(true);
        }

        $manager->merge($survey);
        $manager->flush();

        return $this->json(['code' => 200, 'message' => 'good'], 200);
    }


    /**
     * @Route("/{id}", name="answer")
     */
    public function answer(QuestionRepository $question, Survey $survey = null, Request $request, EntityManagerInterface $manager)
    {
        
        if (!$survey) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

        if ($request->request->count() > 0) {

            $num = $request->request->get("num");
            $questionNum = $question->find($request->request->get("question$num"));

            $answer = new Answer();
            $answer->setQuestion($questionNum)
                   ->setAnswer($request->request->get("postAnswer$num"))
                   ->setCreatedAt(new \DateTime());

            $manager->persist($answer);
            $manager->flush();

            return $this->render('form/answer.html.twig', [
                'survey' => $survey,
            ]);
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
    public function survey(Survey $survey = null, Request $request, EntityManagerInterface $manager)
    {

        if (!$survey) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

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
    public function result(Survey $survey = null)
    {
        if (!$survey) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

    	return $this->render('form/result.html.twig', [
    		'survey' => $survey
    	]);
    }
    /**
     * @Route("/form/delete/{id}", name="delete")
     */
    public function deleteQuestion(QuestionRepository $questionRepo, Question $question = null, EntityManagerInterface $manager) : Response
    {   

        if (!$question) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

        $question = $questionRepo->findOneBy(['id' => $question->getId()]);

        $manager->remove($question);
        $manager->flush();

        return $this->json(['code' => 200, 'message' => 'La question a bien été supprimé.'], 200);
    }

    /**
     * @Route("/form/post/{id}/{value}", name="postAnswer")
     */
    public function postAnswer(QuestionRepository $questionRepo, Question $question = null, EntityManagerInterface $manager, $value) : Response
    {   

        if (!$question) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

        $question = $questionRepo->findOneBy(['id' => $question->getId()]);
        $answers = $question->getAnswers();

        if ($answers->count() > 0) {

            foreach ($answers as $answer) {
                $manager->remove($answer);
                $manager->flush();
            }

        }

        $answer = new Answer();
        $answer->setQuestion($question)
               ->setAnswer($value)
               ->setCreatedAt(new \DateTime());

        $manager->persist($answer);
        $manager->flush();

        return $this->json([
            'code' => 200, 
            'message' => 'la réponse est enregistré', 
            'answer' => $value
        ], 200);
    }
}
