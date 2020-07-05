<?php
declare(strict_types = 1);

namespace WIT\FullStackBootcamp\Blog\Controller;

use WIT\FullStackBootcamp\Blog\Repository;
use WIT\FullStackBootcamp\Common;
use Symfony\Component\HttpFoundation\Response;

class Posts implements Common\Controller
{
    /**
     * @var Common\View
     */
    private $view;

    /**
     * @var Repository\Posts
     */
    private $postsRepository;

    /**
     * @param Common\View $view
     * @param Repository\Posts $postsRepository
     */
    public function __construct(Common\View $view, Repository\Posts $postsRepository)
    {
        $this->view = $view;
        $this->postsRepository = $postsRepository;
    }

    public function run(): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($this->view->get(
            'Blog'
            . \DIRECTORY_SEPARATOR
            . 'View'
            . \DIRECTORY_SEPARATOR
            . 'posts.twig',
            [
                'posts' => $this->getPosts(1, 10)
            ]
        ));

        return $response;
    }

    private function getPosts(): array
    {
        $posts = [];

        foreach ($this->postsRepository->getList() as $post) {
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
}
