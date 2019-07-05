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
    public function view(string $username, UserRepository $repository,
                         UploadRepository $uploadRepository)
    {
        $user = $repository->findOneBy(['username' => $username]);
        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');
        $upload = new Upload();
        $formAvatar = $this->createForm(AvatarType::class, $upload);
        $formBanner = $this->createForm(BannerType::class, $upload);
        $arrayUploads = $uploadRepository->getImages($user);
        dump($arrayUploads);
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'formAvatar' => $formAvatar->createView(),
            'formBanner' => $formBanner->createView(),
            'images' => $arrayUploads
        ]);
    }

    /**
     * @Route("/uploadAvatar", name="uploadAvatar")
     */
    public function uploadAvatar(Request $request, ObjectManager $em)
    {
        $user = $this->getUser();
        $upload = new Upload();
        $upload->setUploader($user);
        $formAvatar = $this->createForm(AvatarType::class, $upload);
        $formAvatar->handleRequest($request);
        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {
            foreach ($user->getUploads() as $key => $item)
                if ($item->getType() === "avatar") {
                    $upload = $user->getUploads()[$key];
                    $lastPic = $this->getParameter('Avatar_directory') .
                        '/' . $upload->getPath();
                    unlink($lastPic);
                }
            $file = $formAvatar['path']->getData();
            $filename = md5(random_bytes(20));
            $file->move($this->getParameter('Avatar_directory'),
                $filename);
            $upload->setPath($filename);
            $upload->setType('avatar');
            $user->addUpload($upload);
            $em->persist($upload);
            $em->flush();
        }
        return $this->redirectToRoute('profile_view', [
            "username" => $user->getUsername()
        ]);
    }

    /**
     * @Route("/uploadBanner", name="uploadBanner")
     */
    public function uploadBanner(Request $request, ObjectManager $em)
    {
        $user = $this->getUser();
        $upload = new Upload();
        $upload->setUploader($user);
        $formBanner = $this->createForm(BannerType::class, $upload);
        $formBanner->handleRequest($request);
        if ($formBanner->isSubmitted() && $formBanner->isValid())
        {
            foreach ($user->getUploads() as $key => $item)
                if ($item->getType() === "banner")
                {
                    $upload = $user->getUploads()[$key];
                    $lastPic = $this->getParameter('Banner_directory').
                        '/'.$upload->getPath();
                    unlink($lastPic);
                }
            $file = $formBanner['path']->getData();
            $filename = md5(random_bytes(20));
            $file->move($this->getParameter('Banner_directory'),
                $filename);
            $upload->setPath($filename);
            $upload->setType('banner');
            $user->addUpload($upload);
            $em->persist($upload);
            $em->flush();
        }
        return $this->redirectToRoute('profile_view', [
            "username" => $user->getUsername()
        ]);
    }

    /**
     * @Route("/{username}/following", name="profile_following")
     */
    public function following(string $username, UserRepository $userRepository,
                              FollowerRepository $repository,
                              UploadRepository $uploadRepository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);
        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');
        $arrayUploads = $uploadRepository->getImages($this->getUser());
        return $this->render('profile/following.html.twig', [
            'user' => $user,
            'images' => $arrayUploads
        ]);
    }

    /**
     * @Route("/{username}/followers", name="profile_followers")
     */
    public function followers(string $username, UserRepository $userRepository,
                              FollowerRepository $repository,
                              UploadRepository $uploadRepository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);
        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');
        $arrayUploads = $uploadRepository->getImages($this->getUser());
        return $this->render('profile/followers.html.twig', [
            'user' => $user,
            'images' => $arrayUploads
        ]);
    }

    /**
     * @Route("/{username}/likes", name="profile_favorites")
     */
    public function favorites(string $username, UserRepository $userRepository,
                              FavoriteRepository $repository,
                              UploadRepository $uploadRepository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);
        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');
        $arrayUploads = $uploadRepository->getImages($this->getUser());
        return $this->render('profile/followers.html.twig', [
            'user' => $user,
            'images' => $arrayUploads
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
