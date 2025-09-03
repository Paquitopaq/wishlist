<?php
//
//namespace App\DataFixturesNotUse;
//
//use App\Entity\Category;
//use App\Entity\User;
//use App\Entity\Wish;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\DataFixturesNotUse\DependentFixtureInterface;
//use Doctrine\Persistence\ObjectManager;
//
//class WishFixtures extends Fixture implements DependentFixtureInterface
//{
//    public function load(ObjectManager $manager): void
//    {
//
//        $faker = \Faker\Factory::create('en_EN');
//        $categories = $manager->getRepository(Category::class)->findAll();
//        //$users = $manager->getRepository(User::class)->findAll();
//        $users = [
//            $this->getReference('user_admin',UserFixtures::class),
//            $this->getReference('user_1',UserFixtures::class),
//            $this->getReference('user_2',UserFixtures::class),
//        ];
//
//        for ($i = 1; $i <= 10; $i++) {
//            $wish = new Wish();
//            $wish->setTitle("Souhait n°$i");
//            $wish->setDescription("Ceci est la description du souhait numéro $i.\nIl est généré automatiquement.");
//            $wish->setUser($faker->randomElement($users));
//            $wish->setIsPublished(true);
//            $wish->setDateCreated(new \DateTimeImmutable(sprintf('-%d days', rand(1, 30))));
//            $wish->setCategory($faker->randomElement($categories));
//            $manager->persist($wish);
//        }
//
//        $manager->flush();
//    }
//    public function getDependencies(): array
//    {
//    return [CategoryFixtures::class,UserFixtures::class];
//    }
//}
