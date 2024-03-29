<?php

declare(strict_types=1);

namespace Vsvietkov\DataStructures\Tests\Queue;

//require_once __DIR__ . '/QueueCase.php';
use Vsvietkov\DataStructures\Tests\Queue\QueueCase;
use Vsvietkov\DataStructures\Queue\CircularQueue;

/**
 * @covers Vsvietkov\DataStructures\Queue\CircularQueue
 * @covers Vsvietkov\DataStructures\Queue\Queue
 */
final class CircularQueueTest extends QueueCase
{
    public function testEnqueue(): void
    {
        $queue = new CircularQueue(3);
        // Test enqueueing a single element
        $this->assertTrue($queue->enqueue(10));
        $this->assertEquals([10, null, null], $this->_getQueueData($queue));

        // Test enqueueing multiple elements
        $this->assertTrue($queue->enqueue(20));
        $this->assertTrue($queue->enqueue(30));
        $this->assertEquals([10, 20, 30], $this->_getQueueData($queue));

        // Test failed enqueueing (queue is full)
        $this->assertFalse($queue->enqueue(40));

        // Move front pointer away from the first element
        $queue->dequeue();
        $queue->dequeue();

        // Test circular feature
        $this->assertTrue($queue->enqueue(40));
        $this->assertTrue($queue->enqueue(50));
        $this->assertEquals([40, 50, 30], $this->_getQueueData($queue));
    }

    public function testDequeue(): void
    {
        $queue = new CircularQueue(3);
        $queue->enqueue(10);
        $queue->enqueue(20);
        $queue->enqueue(30);

        // Test dequeueing single element
        $this->assertEquals(10, $queue->dequeue());
        $this->assertEquals([null, 20, 30], $this->_getQueueData($queue));

        // Test dequeueing with circular feature
        $this->assertEquals(20, $queue->dequeue());
        $queue->enqueue(40);
        $queue->enqueue(50);
        $this->assertEquals(30, $queue->dequeue());
        $this->assertEquals([40, 50, null], $this->_getQueueData($queue));

        // Test dequeueing all elements
        $this->assertEquals(40, $queue->dequeue());
        $this->assertEquals(50, $queue->dequeue());
        $this->assertEquals([null, null, null], $this->_getQueueData($queue));

        // Test dequeueing from empty queue
        $this->assertEquals(null, $queue->dequeue());
    }

    public function testIsFull(): void
    {
        $queue = new CircularQueue(3);

        // Test general case, the queue is full
        $this->assertFalse($queue->isFull());
        $queue->enqueue(10);
        $this->assertFalse($queue->isFull());
        $queue->enqueue(20);
        $this->assertFalse($queue->isFull());
        $queue->enqueue(30);
        $this->assertTrue($queue->isFull());

        // Test that its impossible to increment rear pointer
        $queue->dequeue();
        $this->assertFalse($queue->isFull());
        $queue->enqueue(40);
        $this->assertTrue($queue->isFull());
    }
}
