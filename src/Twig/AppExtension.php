<?php
namespace App\Twig;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    /** @var UserRepository $userRepository */
    private $userRepository;
    private $sfRouter;

    public function __construct(RouterInterface $router, UserRepository $userRepository)
    {
        $this->sfRouter = $router;
        $this->userRepository = $userRepository;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('format_post', [$this, 'format_post'])
        ];
    }

    public function format_post(string $postContent)
    {
        $postContent = htmlspecialchars($postContent);

        $postContent = preg_replace(
            '/(https?|ftp):\/\/(-\.)?([^\s\/?\.#-]+\.?)+(\/[^\s]*)?$/iS',
            "<a href='$0'>$0</a>",
            $postContent);

        $postContent = preg_replace(
            "/#([A-Za-z0-9]\w+)/",
            "<a href='/hashtag/$1'>#$1</a>",
            $postContent);

        $matches = [];
        $match_cnt = preg_match_all("/@([A-Za-z0-9\-_]+)/",
            $postContent,
            $matches);

        if ($match_cnt >= 1)
        {
            foreach($matches[0] as $i => $match)
            {
                $user = $this->userRepository->findOneBy(['username' => $matches[1][$i]]);

                if ($user !== null)
                {
                    $url = $this->sfRouter->generate('profile_view', [
                        'username' => $matches[1][$i]
                    ]);

                    $postContent = str_replace(
                        $match,
                        "<a href='" . $url . "'>$match</a>",
                        $postContent);
                }
            }
        }


        return $postContent;
    }
}