<?php namespace Tests\Unit\Entity;

use FGhazaleh\Entity\Job;
use Tests\TestCase;

class JobTest extends TestCase
{
    /**
     * @test
     * */
    public function it_should_create_a_comment_entity_from_object()
    {
        $object = $this->readItemFromJsonFile('20201.json');

        $comment = new Job($object);

        $this->assertInstanceOf(Job::class, $comment);
        $this->assertSame(20201, $comment->getId());
        $this->assertSame('Justin.tv is looking for a Lead Flash Engineer!', $comment->getTitle());
        $this->assertSame('Justin.tv is the biggest live video site online. We serve hundreds of thousands of video streams a day,Cheers!', $comment->getText());
        $this->assertSame('justin', $comment->getAuthor());
        $this->assertSame(6, $comment->getScore());
        $this->assertSame('http://google.com', $comment->getUrl());
        $this->assertSame('10 years ago', $comment->getTime()->diffForHumans());
    }

}
