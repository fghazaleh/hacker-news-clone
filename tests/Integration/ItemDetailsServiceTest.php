<?php namespace Tests\Integration;

use Tests\WebTestCase;

class ItemDetailsServiceTest extends WebTestCase
{
    /**
     * Testing the feature with real data from API.
     *
     * @test
     * */
    public function it_should_display_list_of_news_stories()
    {
        $client = $this->createClient();
        $response = $client->request('GET','/item/17398159');

        //asserts
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertSame('Show Story',$response->filter('h4')->text());
    }
}