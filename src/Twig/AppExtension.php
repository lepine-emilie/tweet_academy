<?php
namespace App\Twig;

use App\Entity\User;
use App\Repository\UserRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
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
                    $postContent = str_replace(
                        $match,
                        "<a href='/" . $matches[1][$i] . "'>$match</a>",
                        $postContent);
                }
            }
        }

        return $postContent;
    }
}