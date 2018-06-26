<?php namespace FGhazaleh\Support\Contracts;

interface Jsonable
{
    /**
     * Convert data to Json
     *
     * @return string
     * */
    public function toJson():string;
}
