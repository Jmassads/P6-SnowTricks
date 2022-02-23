<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('FR-fr');

        $users = [];
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $userRole = new Role();
        $userRole->setTitle('ROLE_USER');
        $manager->persist($userRole);


        for ($u = 1; $u < 10; $u++) {
            $user = new User();
            $user ->setFirstName('firstname' . $u)
                ->setLastName('lastname' . $u)
                ->setEmail('email')
                ->setUsername('username' . $u)
                ->setPassword('password' . $u)
                ->setRole($adminRole);

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i < 10; $i++) {
            $trick = new Trick();
            $category = new Category();
            $user = $users[mt_rand(0, count($users) - 1)];

            for ($c = 1; $c < 10 ; $c++) {
                $category->setTitle('category' . $i);
                $manager->persist($category);
            }

            $trick
                ->setTitle('title')
                ->setAuthor($user)
                ->setDescription('description')
                ->setCategory($category)
                ->setCreatedAt(new \DateTimeImmutable());

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image
                    ->setImage($faker->imageUrl())
                    ->setTrick($trick);

                $manager->persist($image);
            }

            for ($k = 1; $k <= mt_rand(2, 5); $k++) {
                $video = new Video();
                $video
                    ->setUrl('https://www.youtube.com/embed/tgbNymZ7vqY')
                    ->setTrick($trick);

                $manager->persist($video);
            }

            $manager->persist($trick);

        }

        $manager->flush();
    }
}
