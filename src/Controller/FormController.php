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
use App\Entity\QuestionMultipleChoice;
use App\Entity\Answer;
use App\Form\SurveyType;
use App\Form\QuestionType;
use App\Form\QuestionMultipleChoiceType;
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
     * @Route("/gif/{value}", name="gif")
     */
    public function indexAction(Request $request, $value)
    {   
        $aremplacer = array(",",".",";",":","!","?","(",")","[","]","{","}","\"","'"," ");
        $enremplacement = " ";
        $sansponctuation = trim(str_replace($aremplacer, $enremplacement, $value));
        $separateur = "#[ ]+#";
        $mots = preg_split($separateur, $sansponctuation);
        $phrase = "";
        foreach ($mots as $mot) {
            $phrase .= "$mot+";
        }

        $url = 'http://api.giphy.com/v1/gifs/search?q='.$phrase.'&api_key='.$_ENV['KEY_API_GIPHY'].'&lang=fr&limit=9';
        $obj = json_decode(file_get_contents($url), true);
        $srcs = [];
        $ids = [];
        for ($i=0; $i <= 8; $i++) { 
            array_push($srcs, $obj['data'][$i]['images']['original']['url']);
            array_push($ids, $obj['data'][$i]['id']);
        }

        return $this->json([
            'code' => 200,
            'message' => 'API request is good.',
            'srcs' => $srcs,
            'ids' => $ids,
            '$obj' => $obj
        ], 200);

    }

    /**
     * @Route("/form", name="form")
     */
    public function index(SurveyRepository $repo)
    {   
        $user = $this->getUser();

        if (!$user) {
            return $this->render('error/error.html.twig', [
                'error' => "La question elle est vite répondue : vous n'êtes pas connecté."
            ]);
        }

    	$surveys = $repo->findAll();
        return $this->render('form/index.html.twig', [
            'title' => "Toutes les enquêtes",
            'surveys' => $surveys
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

        return $this->json(['code' => 200, 'message' => 'La question a bien été supprimée.'], 200);
    }

    /**
     * @Route("/form/qcm/{id}", name="qcm_create")
     */
    public function qcmCreate(QuestionRepository $questionRepo, Question $question, Request $request, EntityManagerInterface $manager) {

        $user = $this->getUser();

        if (!$user) {
            return $this->render('error/error.html.twig', [
                'error' => "La question elle est vite répondue : vous n'êtes pas connecté."
            ]);
        }

        if (!$question) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

        $questionMultipleChoice = new QuestionMultipleChoice;

        $form = $this->createFormBuilder($questionMultipleChoice) 
                     ->add('content') 
                     ->getForm();

        $form->handleRequest($request);

        $question = $questionRepo->findOneBy(['id' => $question->getId()]);

        if ($form->isSubmitted() && $form->isValid()) {

            $questionMultipleChoice->setQuestion($question);

            $manager->persist($questionMultipleChoice);
            $manager->flush();

            return $this->redirectToRoute('qcm_create', [
                'id'=>$question->getId()
            ]);

        }

        return $this->render('form/qcm.html.twig', [
            'title' => $question->getQuestion($question),
            'formQCM' => $form->createView(),
            'question'=>$question
        ]);
    }

    /**
     * @Route("/form/post/{id}/{value}", name="postAnswer")
     */
    public function postAnswer(QuestionRepository $questionRepo, Question $question = null, EntityManagerInterface $manager, $value) : Response
    {   
        $user = $this->getUser();

        if (!$question OR !$user) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

        $question = $questionRepo->findOneBy(['id' => $question->getId()]);
        $answers = $question->getAnswers();

        if ($answers->count() > 0) {

            foreach ($answers as $answer) {
                if($answer->getAuthor() === $user) {
                    $manager->remove($answer);
                    $manager->flush();
                }
            }

        }

        $answer = new Answer();
        $answer->setQuestion($question)
               ->setAnswer($value)
               ->setAuthor($user)
               ->setCreatedAt(new \DateTime());

        $manager->persist($answer);
        $manager->flush();

        return $this->json([
            'code' => 200, 
            'message' => 'la réponse est enregistré', 
            'answer' => $value
        ], 200);
    }

    /**
     * @Route("/form/new", name="form_create")
     * @Route("/form/{id}/edit", name="form_edit")
     */
    public function form(Survey $survey = null , Request $request, EntityManagerInterface $manager)
    {   

        $user = $this->getUser();

        if (!$user) {
            return $this->render('error/error.html.twig', [
                'error' => "La question elle est vite répondue : vous n'êtes pas connecté."
            ]);
        }

        if (!$survey) {
            $survey = new Survey();
        }
        
        $form = $this->createForm(SurveyType::class, $survey); 

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$survey->getId()) {
                $survey->setCreatedAt(new \DateTime())
                       ->setStatus(false)
                       ->setAuthor($user);
            }
            
            $manager->persist($survey);
            $manager->flush();

            return $this->redirectToRoute('form_survey', ['id'=>$survey->getId()]);

        }

        return $this->render('form/create.html.twig', [
            'title' => "Créer une enquête",
            'surveyForm' => $form->createView(),
            'editMode' => $survey->getId() !== null
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
     * @Route("/form/{id}", name="form_survey")
     */
    public function survey(Survey $survey = null, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->render('error/error.html.twig', [
                'error' => "La question elle est vite répondue : vous n'êtes pas connecté."
            ]);
        }

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
     * @Route("/result/{id}/{value}", name="form_result")
     */
    public function result(Survey $survey = null, $value = null)
    {

        if (!$survey) {
            return $this->render('error/error.html.twig', [
                'error' => "ERROR 500"
            ]);
        }

    	return $this->render('form/result.html.twig', [
    		'survey' => $survey,
            'value' => $value
    	]);
    }

    /**
     * @Route("/{id}", name="answer")
     */
    public function answer(QuestionRepository $question, Survey $survey = null, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->render('error/error.html.twig', [
                'error' => "La question elle est vite répondue : vous n'êtes pas connecté."
            ]);
        }

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

            // return $this->render('form/answer.html.twig', [
            //     'survey' => $survey,
            // ]);

            return $this->json([
                'code' => 200, 
                'message' => 'la réponse est enregistré', 
                'answer' => $request->request->get("postAnswer$num")
            ], 200);
        }

        return $this->render('form/answer.html.twig', [
            'survey' => $survey
        ]);
    }

}
