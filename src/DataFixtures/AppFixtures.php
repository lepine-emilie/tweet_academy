<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Poutouyemoun');
        $user->setPassword('test');
        $user->setDisplayName('Gros Chef Bandzi');
        $user->setEmail("dev@localhost");
        $manager->persist($user);

        for($i = 1; $i != 5; $i++) {
            $quack = new Post();
            $quack->setSender($user);
            $quack->setContent(str_repeat("quack ", $i));
            $quack->setSubmitTime(new DateTime());
            $quack->setMediaUrl('http://french.peopledaily.com.cn/mediafile/201308/16/F201308160908385111850043.jpg');
            $manager->persist($quack);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
