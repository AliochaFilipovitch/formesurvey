<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Survey;
use App\Entity\Question;
use App\Entity\CategoryQuestion;
use App\Entity\Answer;
use App\Entity\QuestionMultipleChoice;

class SurveyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = \Faker\Factory::create('fr_FR');

		// Créer 3 catégories de questions au Survey
		for($j = 1; $j <= 5; $j++){
			$categoryQuestion = new CategoryQuestion();
			$categoryQuestion->setTitle($faker->word);

			$manager->persist($categoryQuestion);

		}

    	// Créer 5 Survey fakées
		for($i = 1; $i <= 5; $i++){
			$survey = new Survey();
			$survey->setTitle($faker->sentence())
			       ->setDescription($faker->paragraph())
			       ->setAuthor($faker->name)
			       ->setStatus($faker->boolean($chanceOfGettingTrue = 50))
			       ->setCreatedAt($faker->dateTimeBetween('-6 months'));

			$manager->persist($survey);

			// Créer 3 - 5 questions au Survey
			for($k = 1; $k <= mt_rand(3, 5); $k++){
				$question = new Question();
				$question->setQuestion($faker->sentence().' ?')
						 ->setCreatedAt($survey->getCreatedAt())
						 ->setCategoryQuestion($categoryQuestion)
						 ->setSurvey($survey);

				$manager->persist($question);

				// Créer 0 - 2 réponses au Question
				for($l = 0; $l <= mt_rand(0, 2); $l++){
					$answer = new answer();
					$answer->setQuestion($question)
						   ->setAnswer($faker->sentence())
						   ->setCreatedAt($survey->getCreatedAt());

					$manager->persist($answer);
				}
			}

		}

        $manager->flush();
    }
}
