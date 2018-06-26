<?php namespace FGhazaleh\Entity\Traits;

use FGhazaleh\Entity\Comment;

trait ChildCommentTrait
{
    /**
     * @var array
     * */
    private $children = [];

    /**
     * @var array
     * */
    private $kids = [];

    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Return list of kids ids (comments).
     *
     * @return array
     * */
    public function getKids(): array
    {
        return $this->kids;
    }

    /**
     * Determine if the entity has kids (item ids) or not,
     * return true of there are any.
     * */
    public function hasKids(): bool
    {
        return (count($this->getKids()) > 0);
    }

    /**
     * Determine if the entity has children or not,
     * return true of there are any.
     * */
    public function hasChildren(): bool
    {
        return count($this->children);
    }

    /**
     * Add comment to parent comment.
     *
     * @param Comment $comment
     * */
    public function addChild(Comment $comment): void
    {
        array_push($this->children, $comment);
    }
}
