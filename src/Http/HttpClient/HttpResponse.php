<?php namespace FGhazaleh\Http\HttpClient;

class HttpResponse
{
    /**
     * @var int
     */
    private $statusCode;
    private $response;

    public function __construct(int $statusCode, $response = null)
    {
        $this->statusCode = $statusCode;
        $this->response = $response;
    }

    /**
     * @return mixed
     * */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return int;
     * */
    public function getStatusCode():int
    {
        return $this->statusCode;
    }

    /**
     * Return true if the response is okay
     *
     * @return bool
     * */
    public function ok():bool
    {
        return ($this->statusCode === 200 || $this->statusCode === 201);
    }
}
