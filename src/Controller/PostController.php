<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\FavoriteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function like() { }
    public function repost() { }
    public function delete() { }
}
