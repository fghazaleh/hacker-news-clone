<?php namespace Tests\Unit\Services;

use FGhazaleh\Entity\Comment;
use FGhazaleh\Entity\Story;
use FGhazaleh\Exceptions\HttpClientException;
use FGhazaleh\Exceptions\InvalidArgsException;
use FGhazaleh\Http\HttpClient\HttpClientInterface;
use FGhazaleh\Services\HackerNews\ItemDetailsService;
use Tests\Fixtures\FakeHttpClient;
use Tests\WebTestCase;

class ItemDetailsServiceTest extends WebTestCase
{
    /**
     * @test
     * */
    public function it_should_return_a_story_with_comments()
    {
        //mock the HttpClientInterface.
        FakeHttpClient::fake($this->app);

        $httpClient = $this->app[HttpClientInterface::class];

        //create instance of TopStories
        $service = new ItemDetailsService(
            $httpClient,
            $this->app['hacker-news']
        );

        $story = $service->getItemById(10006);

        $httpClient->assertUrl('https://hacker-news.firebaseio.com/v0/item/10006.json');
        $httpClient->assertMethod('GET');

        $this->assertInstanceOf(Story::class, $story);
        $this->assertSame(10006, $story->getId());
        $this->assertSame('This is my story title', $story->getTitle());
        $this->assertCount(3, $story->getKids());
        $this->assertCount(3, $story->getChildren());

        //level 1
        $comment1 = $story->getChildren()[0];
        $this->assertInstanceOf(Comment::class, $comment1);
        $this->assertSame(1000601, $comment1->getId());
        $this->assertCount(1, $comment1->getChildren());

        //level 2
        $this->assertInstanceOf(Comment::class, $comment1->getChildren()[0]);
        $this->assertSame(100060102, $comment1->getChildren()[0]->getId());
        $this->assertCount(0, $comment1->getChildren()[0]->getChildren());

        //level 1
        $comment2 = $story->getChildren()[1];
        $this->assertInstanceOf(Comment::class, $comment2);
        $this->assertSame(1000602, $comment2->getId());
        $this->assertCount(2, $comment2->getChildren());

        //level 2
        $this->assertInstanceOf(Comment::class, $comment2->getChildren()[0]);
        $this->assertSame(100060205, $comment2->getChildren()[0]->getId());
        $this->assertSame(1000602, $comment2->getChildren()[0]->getParent());
        $this->assertCount(0, $comment2->getChildren()[0]->getChildren());

        $this->assertInstanceOf(Comment::class, $comment2->getChildren()[1]);
        $this->assertSame(100060207, $comment2->getChildren()[1]->getId());
        $this->assertSame(1000602, $comment2->getChildren()[1]->getParent());
        $this->assertCount(0, $comment2->getChildren()[1]->getChildren());

        //level 1
        $comment3 = $story->getChildren()[2];
        $this->assertInstanceOf(Comment::class, $comment3);
        $this->assertSame(1000604, $comment3->getId());
        $this->assertCount(0, $comment3->getChildren());

    }

    /**
     * @test
     * */
    public function it_should_throws_a_httpClient_exception()
    {

        $this->expectException(HttpClientException::class);
        //mock the HttpClientInterface.
        FakeHttpClient::fake($this->app);

        //create instance of TopStories
        $service = new ItemDetailsService(
            $this->app[HttpClientInterface::class],
            $this->app['hacker-news']
        );

        $fakeItemId = 123;
        $service->getItemById($fakeItemId);

    }

    /**
     * @test
     * */
    public function it_should_throws_an_exception_with_item_key_not_found_in_config()
    {
        $this->expectException(InvalidArgsException::class);
        $this->expectExceptionMessage('"item" key is missing in config file.');
        //create instance of TopStories
        $service = new ItemDetailsService(
            $this->app[HttpClientInterface::class],
            []
        );
        $service->getItemById(123);
    }
}