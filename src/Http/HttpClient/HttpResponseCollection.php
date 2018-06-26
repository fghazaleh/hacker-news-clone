<?php namespace FGhazaleh\Http\HttpClient;

use FGhazaleh\Support\Collection\Collection;

class HttpResponseCollection extends Collection
{
    /**
     * Add HttpResponse to Collection
     *
     * @param HttpResponse $response
     * @return HttpResponseCollection
     */
    public function addHttpResponse(HttpResponse $response):HttpResponseCollection
    {
        array_push($this->items, $response);
        return $this;
    }
}
