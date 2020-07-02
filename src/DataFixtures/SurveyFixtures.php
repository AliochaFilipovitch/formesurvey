<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Survey;
use App\Entity\Question;
use App\Entity\CategoryQuestion;

class SurveyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = \Faker\Factory::create('fr_FR');

		// Créer 3 catégories de questions au Survey
		for($j = 1; $j <= 3; $j++){
			$categoryQuestion = new CategoryQuestion();
			$categoryQuestion->setTitle($faker->word);

			$manager->persist($categoryQuestion);

		}

    	// Créer 10 Survey fakées
		for($i = 1; $i <= 10; $i++){
			$survey = new Survey();
			$survey->setTitle($faker->sentence())
			       ->setDescription($faker->paragraph())
			       ->setAuthor($faker->name)
			       ->setCreatedAt($faker->dateTimeBetween('-6 months'));

			$manager->persist($survey);

			// Créer 5 questions au Survey
			for($k = 1; $k <= 5; $k++){
				$question = new Question();
				$question->setQuestion($faker->sentence().' ?')
						 ->setCreatedAt($survey->getCreatedAt())
						 ->setCategoryQuestion($categoryQuestion)
						 ->setSurvey($survey);

				$manager->persist($question);
			}

		}

        $manager->flush();
    }
}
