<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Survey;

class SurveyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		for($i = 1; $i < 10; $i++){
			$survey = new Survey();
			$survey->setTitle("Titre de l'enquête n°$i")
			       ->setDescription("<p>Description de l'enquête n°$i</p>")
			       ->setCreatedAt(new \DateTime());

			$manager->persist($survey);
		}

        $manager->flush();
    }
}
