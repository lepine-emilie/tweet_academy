<?php

namespace App\Controller;

use App\Entity\Follower;
use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\Upload;
use App\Entity\User;
use App\Form\AvatarType;
use App\Form\BannerType;
use App\Form\EditProfileType;
use App\Repository\FavoriteRepository;
use App\Repository\FollowerRepository;
use App\Repository\PostRepository;
use App\Repository\UploadRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/{username}", name="profile_view")
     */
    public function view(User $user, PostRepository $postRepository)
    {
        if ($this->getUser() !== null &&
            $user->getId() === $this->getUser()->getId())
        {
            $upload = new Upload();
            $formAvatar = $this->createForm(AvatarType::class, $upload);
            $formBanner = $this->createForm(BannerType::class, $upload);
        }

        $posts = $postRepository->findBy(
            ['sender' => $user, 'parent_post' => null],
            ['submit_time' => 'DESC']
        );

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'formAvatar' => isset($formAvatar) ? $formAvatar->createView() : null,
            'formBanner' => isset($formBanner) ? $formBanner->createView() : null,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/{username}/with_replies", name="profile_view_replies")
     */
    public function view_replies(User $user, PostRepository $postRepository)
    {
        if ($this->getUser() !== null &&
            $user->getId() === $this->getUser()->getId())
        {
            $upload = new Upload();
            $formAvatar = $this->createForm(AvatarType::class, $upload);
            $formBanner = $this->createForm(BannerType::class, $upload);
        }

        $posts = $postRepository->findBy(
            ['sender' => $user],
            ['submit_time' => 'DESC']
        );

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'formAvatar' => isset($formAvatar) ? $formAvatar->createView() : null,
            'formBanner' => isset($formBanner) ? $formBanner->createView() : null,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/{username}/media", name="profile_view_media")
     */
    public function view_media(User $user, PostRepository $postRepository)
    {
        if ($this->getUser() !== null &&
            $user->getId() === $this->getUser()->getId())
        {
            $upload = new Upload();
            $formAvatar = $this->createForm(AvatarType::class, $upload);
            $formBanner = $this->createForm(BannerType::class, $upload);
        }

        $qb = $postRepository->createQueryBuilder('p');
        $posts = $qb
            ->where('p.sender = :user')
            ->andWhere($qb->expr()->isNotNull('p.media_url'))
            ->orderBy('p.submit_time', 'DESC')
            ->setParameter(':user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'formAvatar' => isset($formAvatar) ? $formAvatar->createView() : null,
            'formBanner' => isset($formBanner) ? $formBanner->createView() : null,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/uploadAvatar", name="uploadAvatar")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function uploadAvatar(Request $request, ObjectManager $em)
    {
        $user = $this->getUser();
        $upload = new Upload();
        $upload->setUploader($user);
        $formAvatar = $this->createForm(AvatarType::class, $upload);
        $formAvatar->handleRequest($request);
        if ($formAvatar->isSubmitted() && $formAvatar->isValid())
        {
            foreach ($user->getUploads() as $key => $item)
                if ($item->getType() === "avatar")
                {
                    $upload = $user->getUploads()[$key];
                    $lastPic = $this->getParameter('Avatar_directory') .
                        '/' . $upload->getPath();
                    if (file_exists($lastPic))
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
        return $this->redirectToRoute("profile_view", [
            "username" => $user->getUsername()
        ]);
    }

    /**
     * @Route("/uploadBanner", name="uploadBanner")
     * @IsGranted("ROLE_USER", statusCode=403)
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
                    $lastPic = $this->getParameter('Banner_directory') .
                        '/' . $upload->getPath();
                    if (file_exists($lastPic))
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
    public function following(User $user)
    {
        if ($this->getUser() !== null &&
            $user->getId() === $this->getUser()->getId())
        {
            $upload = new Upload();
            $formAvatar = $this->createForm(AvatarType::class, $upload);
            $formBanner = $this->createForm(BannerType::class, $upload);
        }

        return $this->render('profile/following.html.twig', [
            'user' => $user,
            'formAvatar' => isset($formAvatar) ? $formAvatar->createView() : null,
            'formBanner' => isset($formBanner) ? $formBanner->createView() : null
        ]);
    }

    /**
     * @Route("/{username}/followers", name="profile_followers")
     */
    public function followers(User $user)
    {
        if ($this->getUser() !== null &&
            $user->getId() === $this->getUser()->getId())
        {
            $upload = new Upload();
            $formAvatar = $this->createForm(AvatarType::class, $upload);
            $formBanner = $this->createForm(BannerType::class, $upload);
        }

        return $this->render('profile/followers.html.twig', [
            'user' => $user,
            'formAvatar' => isset($formAvatar) ? $formAvatar->createView() : null,
            'formBanner' => isset($formBanner) ? $formBanner->createView() : null
        ]);
    }

    /**
     * @Route("/{username}/likes", name="profile_favorites")
     */
    public function favorites(User $user)
    {
        if ($this->getUser() !== null &&
            $user->getId() === $this->getUser()->getId())
        {
            $upload = new Upload();
            $formAvatar = $this->createForm(AvatarType::class, $upload);
            $formBanner = $this->createForm(BannerType::class, $upload);
        }

        return $this->render('profile/favorites.html.twig', [
            'user' => $user,
            'formAvatar' => isset($formAvatar) ? $formAvatar->createView() : null,
            'formBanner' => isset($formBanner) ? $formBanner->createView() : null
        ]);
    }

    /**
     * @Route("/{username}/edit", name="edit_profile")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function edit_profile(Request $request, ObjectManager $manager)

    {
        $edit_profile = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $edit_profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($edit_profile);
            $manager->flush();

            return $this->redirectToRoute('profile_view', ["username" =>
                $edit_profile->getUsername()]);
        }
        return $this->render('profile/edit.html.twig', [
            'formEdit' => $form->createView()
        ]);
    }

    /**
     * @Route("/{username}/follow", name="follow_ajax")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function follow(User $otherUser, ObjectManager $manager,
                           FollowerRepository $followerRepository)
    {
        $user = $this->getUser();
        $otherUser->getId();
        $unfollower = $followerRepository->findOneBy(['follower' => $user,
            'user' => $otherUser]);

        if ($otherUser != $user && $unfollower === null)
        {
            $follower = new Follower();
            $follower->setUser($otherUser);
            $follower->setFollower($user);
            $follower->setFollowDate(new DateTime());

            $notification = new Notification();
            $notification->setUser($otherUser);
            $notification->setNotificationType(Notification::TYPE_FOLLOW);
            $notification->setNotificationData([
                'user_id' => $user->getId()
            ]);
            $notification->setIsRead(false);

            $otherUser->addNotification($notification);
            $otherUser->addFollower($follower);

            $manager->persist($notification);
            $manager->persist($follower);
            $manager->flush();

            return new JsonResponse(["success" => true]);
        }
        else
        {
            return new JsonResponse(["success" => false]);
        }
    }

    /**
     * @Route("/{username}/unfollow", name="unfollow_ajax")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function unfollow(User $otherUser, ObjectManager $manager, FollowerRepository $followerRepository)
    {
        $entry = $followerRepository->findOneBy([
            'follower' => $this->getUser(),
            'user' => $otherUser
        ]);

        if ($entry !== null)
        {
            $manager->remove($entry);
            $manager->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }
}