<?php namespace Tests\Integration;

use Tests\WebTestCase;

class NewStoriesTest extends WebTestCase
{
    /**
     * Testing the feature with real data from API.
     *
     * @test
     * */
    public function it_should_display_list_of_top_stories()
    {
        $client = $this->createClient();
        $response = $client->request('GET','/top-stories');

        //asserts
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertSame('Top Stories',$response->filter('h4')->text());
        $node = $response->filterXPath('//div[@class="list-group"]')->children();
        $this->assertContains('<a class="btn btn-primary btn-sm btn-more-news" href="http://localhost/top-stories?page=2" role="button" title="more news">More...</a>',$response->filterXPath('//div[@id="moreNewsContainer"]')->html());
    }

    /**
     * Testing the feature with real data from API.
     *
     * @test
     * */
    public function it_should_display_list_of_new_stories()
    {
        $client = $this->createClient();
        $response = $client->request('GET','/new-stories');

        //asserts
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertSame('New Stories',$response->filter('h4')->text());
        $node = $response->filterXPath('//div[@class="list-group"]')->children();
        $this->assertContains('<a class="btn btn-primary btn-sm btn-more-news" href="http://localhost/new-stories?page=2" role="button" title="more news">More...</a>',$response->filterXPath('//div[@id="moreNewsContainer"]')->html());
    }
}