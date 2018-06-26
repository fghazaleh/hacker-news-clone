<?php namespace FGhazaleh\Support\Collection;

use Countable;
use ArrayAccess;
use FGhazaleh\Support\Contracts\Arrayable;

class Collection implements Arrayable, ArrayAccess, Countable
{
    /**
     * @var array
     * */
    protected $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param array $items
     * @return Collection
     */
    public static function make(array $items):Collection
    {
        return new static($items);
    }

    /**
     * Add item to collection
     *
     * @param mixed $item
     * @return Collection
     */
    public function add($item):Collection
    {
        array_push($this->items, $item);
        return $this;
    }

    /**
     * Slice the array.
     *
     * @param int $offset
     * @param int $length
     * @return Collection;
     */
    public function slice(int $offset, int $length):Collection
    {
        $this->items = array_slice($this->items, $offset, $length);
        return $this;
    }

    /**
     * @param callable $callable
     * @return Collection ;
     */
    public function map(callable $callable):Collection
    {
        $this->items = array_map($callable, $this->items);
        return $this;
    }

    /**
     * Execute a callback over each item.
     *
     * @param  callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

    /**
     * @param callable $where
     * @return Collection ;
     */
    public function where(callable $where):Collection
    {
        $items = array_filter($this->items, $where);
        return new static($items);
    }

    /**
     *
     * @param callable $where
     * @return mixed
     */
    public function first(callable $where)
    {
        $items = $this->where($where)->all();
        return array_shift($items);
    }

    /**
     * @return array
     * */
    public function all():array
    {
        return $this->items;
    }

    /**
     * Convert data to array
     *
     * @return array
     * */
    public function toArray():array
    {
        $items = [];
        foreach ($this->items as $item) {
            if ($item instanceof Arrayable) {
                array_push($items, $item->toArray());
            } else {
                array_push($items, json_decode(json_encode($item), true));
            }
        }
        return $items;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            return;
        }
        unset($this->items[$offset]);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->all());
    }
}
