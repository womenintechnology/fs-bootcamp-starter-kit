<?php
declare(strict_types = 1);

namespace WIT\FullStackBootcamp\Api;

use WIT\FullStackBootcamp\Common;
use WIT\FullStackBootcamp\Blog\Repository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Controller implements Common\Controller
{
    /**
     * @var Repository\Posts
     */
    private $postsRepository;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Repository\Posts $postsRepository
     * @param Request $request
     */
    public function __construct(
        Repository\Posts $postsRepository,
        Request $request
    ) {
        $this->postsRepository = $postsRepository;
        $this->request = $request;
    }

    public function run(): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent(\json_encode($this->getPosts()));

        return $response;
    }

    private function getPosts(): array
    {
        $posts = [];
        $page = $this->getPage();
        $postsPerPage = $this->getPostsPerPage();

        foreach ($this->postsRepository->getList($page, $postsPerPage) as $post) {
            $posts[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'date' => $post->getDateOfPublication()->format('d-m-Y H:i'),
                'author_forename' => $post->getAuthor()->getForename(),
                'author_surname' => $post->getAuthor()->getSurname()
            ];
        }

        return $posts;
    }

    private function getPage(): int
    {
        $page = (int)$this->request->query->get('page');

        if ($page < 1) {
            throw new ResourceNotFoundException("Page can not be lower than 1");
        }

        return $page;
    }

    private function getPostsPerPage(): int
    {
        $postsPerPage = (int)$this->request->query->get('postsPerPage');

        if ($postsPerPage < 1) {
            throw new ResourceNotFoundException("PostsPerPage can not be lower than 1");
        }

        return $postsPerPage;
    }
}
