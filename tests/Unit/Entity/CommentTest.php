<?php namespace Tests\Unit\Entity;

use FGhazaleh\Entity\Comment;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * @test
     * */
    public function it_should_create_a_comment_entity_from_object()
    {
        $object = $this->readItemFromJsonFile('100060102.json');

        $comment = new Comment($object);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertSame(100060102, $comment->getId());
        $this->assertSame('It\'s a deal!', $comment->getText());
        $this->assertSame('cema', $comment->getAuthor());
        $this->assertSame(1000601, $comment->getParent());
        $this->assertSame('6 years ago', $comment->getTime()->diffForHumans());
    }

    /**
     * @test
     * */
    public function it_should_create_a_comment_and_add_two_child_comments()
    {
        $object = $this->readItemFromJsonFile('1000602.json');
        $comment = new Comment($object);

        //assert
        $this->assertCount(2, $comment->getKids());

        //add child comments to parent child
        foreach ($comment->getKids() as $item) {
            $object = $this->readItemFromJsonFile($item . '.json');
            $comment->addChild(new Comment($object));
        }
        //assert comment children.
        $this->assertCount(2, $comment->getChildren());
        $this->assertInstanceOf(Comment::class, $comment->getChildren()[0]);
        $this->assertSame(100060205, $comment->getChildren()[0]->getId());
        $this->assertSame(100060207, $comment->getChildren()[1]->getId());
        $this->assertSame(1000602, $comment->getChildren()[0]->getParent());
        $this->assertSame(1000602, $comment->getChildren()[1]->getParent());

    }
}
