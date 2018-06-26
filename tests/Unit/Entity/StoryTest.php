<?php namespace Tests\Unit\Entity;

use FGhazaleh\Entity\Comment;
use Tests\TestCase;
use FGhazaleh\Entity\Story;

class StoryTest extends TestCase
{
    /**
     * @test
     * */
    public function it_should_create_a_story_entity_from_object()
    {
        $object = $this->readItemFromJsonFile('10006.json');

        $story = new Story($object);

        $this->assertInstanceOf(Story::class, $story);
        $this->assertSame(10006, $story->getId());
        $this->assertSame('This is my story title', $story->getTitle());
        $this->assertSame('Franco', $story->getAuthor());
        $this->assertSame(300, $story->getScore());
        $this->assertSame('fake.example.com', $story->getSite());
        $this->assertSame('3 years ago', $story->getTime()->diffForHumans());
    }

    /**
     * @test
     * */
    public function it_should_create_a_story_and_add_comments_children()
    {
        $object = $this->readItemFromJsonFile('10006.json');
        $story = new Story($object);

        //assert
        $this->assertInstanceOf(Story::class, $story);
        $this->assertSame(10006, $story->getId());
        $this->assertCount(3, $story->getKids());

        //add child comments to parent child
        foreach ($story->getKids() as $item) {
            $object = $this->readItemFromJsonFile($item . '.json');
            $story->addChild(new Comment($object));
        }

        //assert children
        $this->assertCount(3, $story->getChildren());
        $this->assertInstanceOf(Comment::class, $story->getChildren()[0]);
        $this->assertSame(1000601, $story->getChildren()[0]->getId());
        $this->assertSame(1000602, $story->getChildren()[1]->getId());
        $this->assertSame(1000604, $story->getChildren()[2]->getId());

        $this->assertSame(10006, $story->getChildren()[0]->getParent());
        $this->assertSame(10006, $story->getChildren()[1]->getParent());
        $this->assertSame(10006, $story->getChildren()[2]->getParent());

        $this->assertCount(1, $story->getChildren()[0]->getKids());
        $this->assertCount(2, $story->getChildren()[1]->getKids());
        $this->assertCount(0, $story->getChildren()[2]->getKids());
    }
}