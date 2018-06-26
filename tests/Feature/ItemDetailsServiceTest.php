<?php namespace Tests\Feature;

use Symfony\Component\DomCrawler\Crawler;
use Tests\Fixtures\FakeHttpClient;
use Tests\WebTestCase;

class ItemDetailsServiceTest extends WebTestCase
{
    /**
     * Testing the feature with fakeHttpClient
     *
     * @test
     * */
    public function it_should_display_a_story_with_list_of_nested_comments()
    {
        FakeHttpClient::fake($this->app);

        $client = $this->createClient();
        $response = $client->request('GET','/item/10006');

        $this->assertTrue($client->getResponse()->isOk(),'"/item/10006" routes returning error.');
        $this->assertSame('Show Story',$response->filter('h4')->text());
        $node = $response->filterXPath('//div[@id="commentsContainer"]')->children();
        $this->assertCount(6,$node);
        $node->each(function(Crawler $node, $i){
            if ($i == 0) {
                $html = $node->children()->html();
                $this->assertContains('By <a class="color-gray" href="https://news.ycombinator.com/user?id=Comment_user1">Comment_user1</a> 6 years ago',$html);
                $this->assertContains('<p class="card-text">Aw shucks, guys ... you make me blush with your compliments.Tell you what, Ill make a deal: I\'ll keep writing if you keep reading. K?</p>',$html);
            }
            return;
        });

    }
}