<?php

namespace Tests;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertClassUsesTrait($trait, $class)
    {
        $this->assertArrayHasKey(
            $trait,
            class_uses($class),
            "{$class} class must use {$trait} trait"
        );
    }

    /** @test */
    protected function assertDontBroadCastToCurrentUser($event, $socketId = 'socket-id')
    {
        $this->assertInstanceOf(ShouldBroadcast::class, $event);

        $this->assertEquals(
            $socketId, // Generated by Broadcast::shouldReceive('socket')->andReturn('socket-id');
            $event->socket,
            'The event ' . get_class($event) . ' must call the method "dontBroadcastToCurrentUser" in the constructor.'
        );
    }

    /** @test
     * @param $channelType
     * @param $event
     */
    protected function assertEventChannelType($channelType, $event)
    {
        $types = [
            'public' => \Illuminate\Broadcasting\Channel::class,
            'private' => \Illuminate\Broadcasting\PrivateChannel::class,
            'presence' => \Illuminate\Broadcasting\PresenceChannel::class,
        ];
        $this->assertEquals($types[$channelType], get_class($event->broadcastOn()));
    }

    /** @test
     * @param $channelName
     * @param $event
     */
    protected function assertEventChannelName($channelName, $event)
    {
        $this->assertEquals($channelName, $event->broadcastOn()->name);
    }


}
