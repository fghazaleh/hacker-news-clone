<?php namespace Tests\Feature;

use Symfony\Component\DomCrawler\Crawler;
use Tests\Fixtures\FakeHttpClient;
use Tests\WebTestCase;

class NewStoriesTest extends WebTestCase
{
    /**
     * Testing the feature with fakeHttpClient
     *
     * @test
     * */
    public function it_should_display_list_of_new_stories()
    {
        FakeHttpClient::fake($this->app);

        $client = $this->createClient();
        $response = $client->request('GET','/new-stories');

        $this->assertTrue($client->getResponse()->isOk(),'"/top-stories" routes returning error.');
        $this->assertSame('New Stories',$response->filter('h4')->text());
        $node = $response->filterXPath('//div[@class="list-group"]')->children();
        $this->assertCount(5,$node);
        $node->each(function(Crawler $node, $i){
            if ($i == 0) {
                $htmlPart1 = $node->children()->eq(0)->html();
                $this->assertContains('<a href="http://fake.example.com/fake-path/fake-page.html?foo=bar">This is my story title</a>', $htmlPart1);
                $this->assertContains('<small class="color-gray">(<a href="https://news.ycombinator.com/user?id=fake.example.com">fake.example.com</a>)</small>', $htmlPart1);
                $this->assertContains('<small>3 years ago</small>', $htmlPart1);

                $htmlPart2 = $node->children()->eq(1)->html();
                $this->assertContains('<span class="badge badge-primary badge-pill">300</span> points by',$htmlPart2);
                $this->assertContains('<a href="https://news.ycombinator.com/user?id=Franco" target="_blank">Franco</a> |',$htmlPart2);
                $this->assertContains('<span class="comments"><a href="http://localhost/item/10006">4 comments</a></span>',$htmlPart2);
            }
            return;
        });
        $this->assertContains('<a class="btn btn-primary btn-sm btn-more-news" href="http://localhost/new-stories?page=2" role="button" title="more news">More...</a>',$response->filterXPath('//div[@id="moreNewsContainer"]')->html());
    }
}