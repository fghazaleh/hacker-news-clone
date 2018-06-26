<?php namespace FGhazaleh\Entity;

use Carbon\Carbon;
use FGhazaleh\Entity\Contacts\HackerNewsEntityInterface;
use FGhazaleh\Entity\Traits\ChildCommentTrait;
use FGhazaleh\Support\Helper\Helper;

class Story implements HackerNewsEntityInterface
{
    use ChildCommentTrait;
    /**
     * @var int
     * */
    private $id;

    /**
     * @var string
     * */
    private $title;

    /**
     * @var string
     * */
    private $url;

    /**
     * @var Carbon
     * */
    private $time;

    /**
     * @var int
     * */
    private $score;

    /**
     * @var string
     * */
    private $by;

    /**
     * @var int
     * */
    private $descendants;

    public function __construct($item)
    {
        $this->id = isset($item->id) ? $item->id : null;
        $this->title = isset($item->title) ? $item->title : null;
        $this->url = isset($item->url) ? $item->url : null;
        $this->time = isset($item->time) ? Carbon::createFromTimestamp($item->time) : null;
        $this->score = isset($item->score) ? $item->score : null;
        $this->by = isset($item->by) ? $item->by : null;
        $this->descendants = isset($item->descendants) ? $item->descendants : null;
        $this->kids = isset($item->kids) ? $item->kids : [];
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function getTitle():?string
    {
        return $this->title;
    }

    public function getUrl():?string
    {
        return $this->url;
    }

    public function getTime():Carbon
    {
        return !is_null($this->time) ? $this->time : Carbon::now();
    }

    public function getTimeForHumans():string
    {
        return $this->getTime()->diffForHumans();
    }


    public function getScore():?int
    {
        return $this->score;
    }

    public function getAuthor():?string
    {
        return $this->by;
    }


    public function getSite():?string
    {
        if (is_null($url = $this->getUrl())) {
            return null;
        }
        return Helper::getHost($url);
    }

    public function getCommentsCount():?int
    {
        return $this->descendants;
    }

    /**
     * Determine if there is a url or not.
     * return false if has no url.
     *
     * @return bool;
     * */
    public function hasUrl():bool
    {
        return ($this->getUrl() != '' && $this->getUrl() != null);
    }
}
