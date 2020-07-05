<?php
declare(strict_types = 1);

namespace WIT\FullStackBootcamp\Blog\Model;

class PostView
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $dateOfPublication;

    /**
     * @var AuthorView
     */
    private $author;

    /**
     * @param int $id
     * @param string $title
     * @param string $content
     * @param \DateTime $dateOfPublication
     * @param AuthorView $author
     */
    public function __construct(
        int $id,
        string $title,
        string $content,
        \DateTime $dateOfPublication,
        AuthorView $author
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->dateOfPublication = $dateOfPublication;
        $this->author = $author;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfPublication(): \DateTime
    {
        return $this->dateOfPublication;
    }

    /**
     * @return AuthorView
     */
    public function getAuthor(): AuthorView
    {
        return $this->author;
    }
}
