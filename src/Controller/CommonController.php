<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommonController extends AbstractController
{
    public function miniProfile()
    {
        return $this->render('common/mini_profile.html.twig', [
            'post_count' => 69,
            'followers_count' => 1337
        ]);
    }
    public function trends()
    {
        return $this->render('common/trends.html.twig');
    }

    public function footer()
    {
        return $this->render('common/notafooter.html.twig');
    }
}
