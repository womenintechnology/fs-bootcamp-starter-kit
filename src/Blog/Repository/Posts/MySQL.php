<?php
declare(strict_types = 1);

namespace WIT\FullStackBootcamp\Blog\Repository\Posts;

use WIT\FullStackBootcamp\Blog\Repository\Posts;
use WIT\FullStackBootcamp\Blog\Model;

class MySQL implements Posts
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var \DateTimeZone
     */
    private $timezone;

    /**
     * @param PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->timezone = new \DateTimeZone('Europe/Warsaw');
    }

    public function getList(int $page = 1, int $postsPerPage = 10): array
    {
        $start = ($page - 1) * $postsPerPage;
        $sql = $this->getQuery($postsPerPage, $start)
            . " ORDER BY Posts.published DESC LIMIT {$postsPerPage} OFFSET {$start}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $posts = [];

        foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $posts[] = $this->createModel($row);
        }

        return $posts;
    }

    public function getOne(int $id): ?Model\PostView
    {
        $sql = $this->getQuery() . " WHERE Posts.id={$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return empty($row) ? null : $this->createModel($row);
    }

    private function getQuery(): string
    {
        return <<<SQL
            SELECT
                Posts.id,
                Posts.title,
                Posts.content,
                Posts.published,
                Authors.id as author_id,
                Authors.forename as author_forename,
                Authors.surname as author_surname,
                Authors.email as author_email
            FROM Posts
            LEFT JOIN Authors ON Posts.author_id=Authors.id
        SQL;
    }

    private function createModel(array $row): Model\PostView
    {
        return new Model\PostView(
            (int) $row['id'],
            $row['title'],
            $row['content'],
            new \DateTime($row['published'], $this->timezone),
            new Model\AuthorView(
                (int) $row['author_id'],
                $row['author_forename'],
                $row['author_surname'],
                $row['author_email']
            )
        );
    }
}
