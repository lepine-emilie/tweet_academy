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
        $users = [
            ['u' => 'LouisonD', 'n' => 'Xie Xie'],
            ['u' => 'EmilieL', 'n' => 'Poutouyemoun'],
            ['u' => 'MerillT', 'n' => 'Le Canard Jaune'],
            ['u' => 'LouisM', 'n' => 'Croix V Bâton'],
        ];

        foreach($users as $u)
        {
            $user = new User();
            $user->setUsername($u['u']);
            $user->setPassword('test');
            $user->setDisplayName($u['n']);
            $user->setEmail("dev@localhost");
            $manager->persist($user);

            for($i = 1; $i != 2; ++$i) {
                $quack = new Post();
                $quack->setSender($user);
                $quack->setContent(str_repeat("quack ", $i));
                $quack->setSubmitTime(new DateTime());
                $quack->setMediaUrl('https://ichef.bbci.co.uk/news/660/media/images/76352000/jpg/_76352619_76346795.jpg');
                $manager->persist($quack);
            }
        }

        $manager->flush();
    }
}
