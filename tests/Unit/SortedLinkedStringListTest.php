<?php

namespace Mustafin\SLL\Tests\Unit;

use InvalidArgumentException;
use Mustafin\SLL\Collections\Lists\SortedLinkedStringList;
use Mustafin\SLL\Collections\Nodes\StringNode;
use Mustafin\SLL\Tests\TestCaseBase;

class SortedLinkedStringListTest extends TestCaseBase
{
    public function testAddStringNode(): void
    {
        $list = SortedLinkedStringList::create(['c', 'f', 'd']);

        $list->add('e');
        self::assertEquals(['c', 'd', 'e', 'f'], $list->getList(), 'Failed to insert new node to the middle');

        $list->add('a');
        self::assertEquals(['a', 'c', 'd', 'e', 'f'], $list->getList(), 'Failed to insert new node to the beginning');

        $list->add('g');
        self::assertEquals(['a', 'c', 'd', 'e', 'f', 'g'], $list->getList(), 'Failed to insert new node to the end');
    }

    public function testSuccessfulRemoveStringNode(): void
    {
        $list = SortedLinkedStringList::create(['c', 'e', 'f', 'a', 'd', 'g']);
        self::assertEquals(['a', 'c', 'd', 'e', 'f', 'g'], $list->getList(), 'Failed to insert new node to the end');

        $nodeToRemove = $list->getHead()?->getNext()?->getNext();
        self::assertInstanceOf(StringNode::class, $nodeToRemove, 'Node to remove not found');
        self::assertEquals('d', $nodeToRemove->getValue());
        $list->remove($nodeToRemove);
        self::assertEquals(['a', 'c', 'e', 'f', 'g'], $list->getList(), 'Failed to remove node from the middle');

        $nodeToRemove = $list->getHead();
        self::assertInstanceOf(StringNode::class, $nodeToRemove, 'Node to remove not found');
        self::assertEquals('a', $nodeToRemove->getValue());
        $list->remove($nodeToRemove);
        self::assertEquals(['c', 'e', 'f', 'g'], $list->getList(), 'Failed to remove node from the beginning');

        $nodeToRemove = $list->getHead()?->getNext()?->getNext()?->getNext();
        self::assertInstanceOf(StringNode::class, $nodeToRemove, 'Node to remove not found');
        self::assertEquals('g', $nodeToRemove->getValue());
        $list->remove($nodeToRemove);
        self::assertEquals(['c', 'e', 'f'], $list->getList(), 'Failed to remove node from the end');
    }

    public function testUnsuccessfulRemoveStringNode(): void
    {
        $list = SortedLinkedStringList::create(['c', 'f', 'e']);
        self::assertEquals(['c', 'e', 'f'], $list->getList(), 'Failed to create initial list');

        $otherList = SortedLinkedStringList::create(['c', 'f', 'e']);
        self::assertEquals(['c', 'e', 'f'], $otherList->getList(), 'Failed to create initial list');

        $otherListHead = $otherList->getHead();
        $otherListMiddle = $otherListHead?->getNext();
        $otherListTail = $otherListMiddle?->getNext();
        self::assertInstanceOf(StringNode::class, $otherListHead, 'Other list node not found');
        self::assertInstanceOf(StringNode::class, $otherListMiddle, 'Other list node not found');
        self::assertInstanceOf(StringNode::class, $otherListTail, 'Other list node not found');

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

        self::assertEquals(['c', 'e', 'f'], $list->getList(), 'List should not be modified during test');
    }
}
