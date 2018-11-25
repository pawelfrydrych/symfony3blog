<?php
/**
 * Created by PhpStorm.
 * User: Pawel
 * Date: 2018-11-25
 * Time: 18:11
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPostData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();


        for ($i = 0; $i < 1000; $i++) {
           $post = new Post();
           $post->setTitle($faker->sentence(5));
           $post->setLead($faker->text(300));
           $post->setContent($faker->text(500));
           $post->setCreatedAt($faker->dateTimeThisMonth);

           $manager->persist($post);
        }

        $manager->flush();
    }
}