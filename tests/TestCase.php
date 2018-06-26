<?php namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    /**
     * Use to read item from json file with provided filename.
     *
     * @param string $file Filename in data/items path.
     * @return mixed
     * */
    protected function readItemFromJsonFile(string $file)
    {
        $path = __DIR__ . '/data/items/' . $file;
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('file path not found.');
        }
        return json_decode(file_get_contents($path));
    }
}