<?php
//
//namespace App\DataFixturesNotUse;
//
//use App\Entity\User;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Persistence\ObjectManager;
//use Faker\Factory;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//
//class UserFixtures extends Fixture
//{
//    public function load(ObjectManager $manager): void
//    {
//        $faker = Factory::create('fr_FR');
//
//        // Récupération des utilisateurs via références
//        $users = [
//            $this->getReference('user_admin',UserFixtures::class),
//            $this->getReference('user_1',User::class),
//            $this->getReference('user_2',User::class),
//            $this->getReference('user_3',User::class),
//        ];
//
//        // Récupération des catégories via références
//        $categories = [
//            $this->getReference('category_voyage'),
//            $this->getReference('category_sport'),
//            $this->getReference('category_culture'),
//            $this->getReference('category_aventure'),
//            $this->getReference('category_solidarité'),
//        ];
//
//        for ($i = 1; $i <= 10; $i++) {
//            $wish = new Wish();
//            $wish->setTitle("Souhait n°$i");
//            $wish->setDescription($faker->paragraph(2));
//            $wish->setUser($faker->randomElement($users));
//            $wish->setIsPublished(true);
//            $wish->setDateCreated(new \DateTimeImmutable(sprintf('-%d days', rand(1, 30))));
//            $wish->setCategory($faker->randomElement($categories));
//            $manager->persist($wish);
//        }
//
//        $manager->flush();
//    }
//
//    public function getDependencies(): array
//    {
//        return [
//            UserFixtures::class,
//            CategoryFixtures::class,
//        ];
//    }
//}
