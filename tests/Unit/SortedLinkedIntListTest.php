<?php

declare(strict_types=1);

namespace Mustafin\SLL\Tests\Unit;

use InvalidArgumentException;
use Mustafin\SLL\Collections\Lists\SortedLinkedIntList;
use Mustafin\SLL\Collections\Nodes\IntNode;
use Mustafin\SLL\Tests\TestCaseBase;

class SortedLinkedIntListTest extends TestCaseBase
{
    public function testAddIntNode(): void
    {
        $list = SortedLinkedIntList::create([3, 9, 6]);

        $list->add(4);
        self::assertEquals([3, 4, 6, 9], $list->getList(), 'Failed to insert new node to the middle');

        $list->add(1);
        self::assertEquals([1, 3, 4, 6, 9], $list->getList(), 'Failed to insert new node to the beginning');

        $list->add(10);
        self::assertEquals([1, 3, 4, 6, 9, 10], $list->getList(), 'Failed to insert new node to the end');
    }

    public function testSuccessRemoveIntNode(): void
    {
        $list = SortedLinkedIntList::create([6, 4, 3, 10, 9, 1]);
        self::assertEquals([1, 3, 4, 6, 9, 10], $list->getList(), 'Failed to create initial list');

        $nodeToRemove = $list->getHead()?->getNext()?->getNext();
        self::assertInstanceOf(IntNode::class, $nodeToRemove, 'Node to remove not found');
        self::assertEquals(4, $nodeToRemove->getValue());
        $list->remove($nodeToRemove);
        self::assertEquals([1, 3, 6, 9, 10], $list->getList(), 'Failed to remove node from the middle');

        $nodeToRemove = $list->getHead();
        self::assertInstanceOf(IntNode::class, $nodeToRemove, 'Node to remove not found');
        self::assertEquals(1, $nodeToRemove->getValue());
        $list->remove($nodeToRemove);
        self::assertEquals([3, 6, 9, 10], $list->getList(), 'Failed to remove node from the beginning');

        $nodeToRemove = $list->getHead()?->getNext()?->getNext()?->getNext();
        self::assertInstanceOf(IntNode::class, $nodeToRemove, 'Node to remove not found');
        self::assertEquals(10, $nodeToRemove->getValue());
        $list->remove($nodeToRemove);
        self::assertEquals([3, 6, 9], $list->getList(), 'Failed to remove node from the end');
    }

    public function testUnsuccessfulRemoveIntNode(): void
    {
        $list = SortedLinkedIntList::create([3, 9, 6]);
        self::assertEquals([3, 6, 9], $list->getList(), 'Failed to create initial list');

        $otherList = SortedLinkedIntList::create([3, 9, 6]);
        self::assertInstanceOf(SortedLinkedIntList::class, $otherList, 'Failed to create SortedLinkedIntList instance');
        self::assertEquals([3, 6, 9], $otherList->getList(), 'Failed to create initial list');

        $otherListHead = $otherList->getHead();
        $otherListMiddle = $otherListHead?->getNext();
        $otherListTail = $otherListMiddle?->getNext();
        self::assertInstanceOf(IntNode::class, $otherListHead, 'Other list node not found');
        self::assertInstanceOf(IntNode::class, $otherListMiddle, 'Other list node not found');
        self::assertInstanceOf(IntNode::class, $otherListTail, 'Other list node not found');

        try {
            $list->remove($otherListHead);
            self::fail('Expected InvalidArgumentException not thrown');
        } catch (InvalidArgumentException $e) {
            self::assertSame("Node doesn't belong to current list", $e->getMessage());
        }

        try {
            $list->remove($otherListMiddle);
            self::fail('Expected InvalidArgumentException not thrown');
        } catch (InvalidArgumentException $e) {
            self::assertSame("Node doesn't belong to current list", $e->getMessage());
        }

        try {
            $list->remove($otherListTail);
            self::fail('Expected InvalidArgumentException not thrown');
        } catch (InvalidArgumentException $e) {
            self::assertSame("Node doesn't belong to current list", $e->getMessage());
        }

        self::assertEquals([3, 6, 9], $list->getList(), 'List should not be modified during test');
    }
}
