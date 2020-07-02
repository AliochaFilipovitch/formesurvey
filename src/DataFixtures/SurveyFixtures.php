<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Survey;
use App\Entity\Question;

class SurveyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = \Faker\Factory::create('fr_FR');

		for($i = 1; $i < 10; $i++){
			$survey = new Survey();
			$survey->setTitle($faker->sentence())
			       ->setDescription($faker->paragraph())
			       ->setAuthor($faker->name)
			       ->setCreatedAt($faker->dateTimeBetween('-6 months'));

			$manager->persist($survey);

			// On donne 4 questions au Survey
			for($j = 1; $j < 5; $j++){
				$question = new Question();
				$question->setQuestion($faker->sentence().' ?')
						 ->setNumQuestion($faker->word)
						 ->setCreatedAt($survey->getCreatedAt())
						 ->setSurvey($survey);

				$manager->persist($question);
			}
		}

        $manager->flush();
    }
}
