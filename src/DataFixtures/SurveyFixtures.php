<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Survey;
use App\Entity\Question;
use App\Entity\CategoryQuestion;
use App\Entity\QuestionMultipleChoice;
use App\Entity\Answer;

class SurveyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = \Faker\Factory::create('fr_FR');

		// Créer 6 catégories de questions au Survey
		for($j = 1; $j <= 6; $j++){
			$categoryQuestion = new CategoryQuestion();
			$categoryQuestion->setTitle($faker->word);

			$manager->persist($categoryQuestion);

		}

		for($i = 1; $i <= 3; $i++){
		// Créer 3 users
			$user = new User();
			$user->setUsername($faker->name)
				 ->setEmail($faker->email)
				 ->setPassword('formesurvey');

			$manager->persist($user);


    	// Créer 5 Survey fakées
		for($k = 1; $k <= 5; $k++){
			$survey = new Survey();
			$survey->setTitle($faker->sentence())
			       ->setDescription($faker->paragraph())
			       ->setStatus($faker->boolean($chanceOfGettingTrue = 50))
			       ->setCreatedAt($faker->dateTimeBetween('-6 months'))
			       ->setAuthor($user);

			$manager->persist($survey);

			// Créer 3 - 5 questions au Survey
			for($l = 1; $l <= mt_rand(3, 5); $l++){
				$question = new Question();
				$question->setQuestion($faker->sentence().' ?')
						 ->setCreatedAt($survey->getCreatedAt())
						 ->setCategoryQuestion($categoryQuestion)
						 ->setSurvey($survey);

				$manager->persist($question);

				// Créer 0 - 2 réponses au Question
				for($m = 0; $m <= mt_rand(0, 2); $m++){
					$answer = new answer();
					$answer->setQuestion($question)
						   ->setAnswer($faker->sentence())
						   ->setAuthor($user)
						   ->setCreatedAt($survey->getCreatedAt());

					$manager->persist($answer);
				}
			}

		}

		}
        $manager->flush();
    }
}
