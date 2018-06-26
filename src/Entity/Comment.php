<?php namespace FGhazaleh\Entity;

use Carbon\Carbon;
use FGhazaleh\Entity\Contacts\HackerNewsEntityInterface;
use FGhazaleh\Entity\Traits\ChildCommentTrait;

class Comment implements HackerNewsEntityInterface
{
    use ChildCommentTrait;

    /**
     * @var int
     * */
    private $id;

    /**
     * @var string
     * */
    private $text;

    /**
     * @var Carbon
     * */
    private $time;

    /**
     * @var string
     * */
    private $by;

    /**
     * @var int
     * */
    private $parent = null;

    public function __construct($item)
    {
        $this->id = isset($item->id) ? $item->id : null;
        $this->text = isset($item->text) ? $item->text : null;
        $this->time = isset($item->time) ? Carbon::createFromTimestamp($item->time) : null;
        $this->by = isset($item->by) ? $item->by : null;
        $this->parent = isset($item->parent) ? $item->parent : null;
        $this->kids = isset($item->kids) ? $item->kids : [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getTime(): Carbon
    {
        return !is_null($this->time) ? $this->time : Carbon::now();
    }

    public function getAuthor(): ?string
    {
        return $this->by;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function getTimeForHumans():string
    {
        return $this->getTime()->diffForHumans();
    }
}
