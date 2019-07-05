<?php

namespace App\Controller;

use App\Form\EditProfileType;
use App\Repository\FavoriteRepository;
use App\Repository\FollowerRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/{username}", name="profile_view")
     */
    public function view(string $username, UserRepository $repository)
    {
        $user = $repository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{username}/following", name="profile_following")
     */
    public function following(string $username, UserRepository $userRepository, FollowerRepository $repository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/following.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{username}/followers", name="profile_followers")
     */
    public function followers(string $username, UserRepository $userRepository, FollowerRepository $repository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/followers.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{username}/likes", name="profile_favorites")
     */
    public function favorites(string $username, UserRepository $userRepository, FavoriteRepository $repository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/favorites.html.twig', [
            'user' => $user
        ]);
    }
    /**
     * @Route("/{username}/edit", name="edit_profile")
     */
    public function edit_profile(Request $request, ObjectManager $manager)

    {
        $edit_profile = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $edit_profile);

        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){
            $manager->persist($edit_profile);
            $manager->flush();

            return $this->redirectToRoute('profile_view', ["username" => $edit_profile->getUsername()]);
        }
        return $this->render('profile/edit.html.twig', [
            'formEdit' => $form->createView()
        ]);
    }
}
