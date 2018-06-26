<?php namespace Tests\Unit\Services;

use Carbon\Carbon;
use FGhazaleh\Entity\Story;
use FGhazaleh\Http\HttpClient\HttpClientInterface;
use FGhazaleh\Services\HackerNews\NewsStoriesService;
use FGhazaleh\Support\Collection\Collection;
use Tests\Fixtures\FakeHttpClient;
use Tests\WebTestCase;

class TopStoriesServiceTest extends WebTestCase
{

    /**
     * @test
     * */
    public function it_should_return_list_of_top_stories()
    {
        //mock the HttpClientInterface.
        FakeHttpClient::fake($this->app);

        $client = $this->app[HttpClientInterface::class];

        //create instance of TopStories
        $service = new NewsStoriesService(
            $client,
            $this->app['hacker-news']
        );

        $list = $service->topStories();

        // asserts
        $client->assertUrl('https://hacker-news.firebaseio.com/v0/topstories.json');
        $client->assertMethod('GET');

        $this->assertInstanceOf(FakeHttpClient::class,$this->app[HttpClientInterface::class]);
        $this->assertInstanceOf(Collection::class,$list);
        $this->assertInstanceOf(Story::class,$list[0]);
        $this->assertCount(4,$list);
        $this->assertSame(10005,$list[0]->getId());
        $this->assertSame('My YC app: Dropbox - Throw away your USB drive',$list[0]->getTitle());
        $this->assertSame(111,$list[0]->getScore());
        $this->assertInstanceOf(Carbon::class,$list[0]->getTime());
        $this->assertSame('http://www.getdropbox.com/u/2/screencast.html',$list[0]->getUrl());
        $this->assertSame(2,$list[0]->getCommentsCount());
    }

    /**
     * @test
     * */
    public function it_should_return_empty_list_of_top_stories_when_page_is_two()
    {
        //mock the HttpClientInterface.
        FakeHttpClient::fake($this->app);

        //create instance of TopStories
        $service = new NewsStoriesService(
            $this->app[HttpClientInterface::class],
            $this->app['hacker-news']
        );

        $page = 2;
        $list = $service->topStories($page);

        // asserts
        $this->assertInstanceOf(FakeHttpClient::class,$this->app[HttpClientInterface::class]);
        $this->assertInstanceOf(Collection::class,$list);
        $this->assertCount(0,$list);
    }
}