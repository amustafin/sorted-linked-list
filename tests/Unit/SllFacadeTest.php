<?php

declare(strict_types=1);

namespace Mustafin\SLL\Tests\Unit;

use InvalidArgumentException;
use Mustafin\SLL\Collections\Lists\SortedLinkedIntList;
use Mustafin\SLL\Collections\Lists\SortedLinkedStringList;
use Mustafin\SLL\Collections\Nodes\IntNode;
use Mustafin\SLL\Collections\Nodes\StringNode;
use Mustafin\SLL\SllFacade;
use Mustafin\SLL\Tests\TestCaseBase;

class SllFacadeTest extends TestCaseBase
{
    private SllFacade $sllFacade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sllFacade = new SllFacade();
    }

    /**
     * @dataProvider getIntStructures
     * @param list<int> $input
     */
    public function testIntType(array $input): void
    {
        self::assertInstanceOf(SortedLinkedIntList::class, $this->sllFacade->getSortedLinkedList($input));
    }

    /**
     * @dataProvider getStringStructures
     * @param list<string> $input
     */
    public function testStringType(array $input): void
    {
        self::assertInstanceOf(SortedLinkedStringList::class, $this->sllFacade->getSortedLinkedList($input));
    }

    /**
     * @dataProvider getStructures
     * @param list<string|int> $input
     * @param list<string|int> $expected
     */
    public function testCreateList(array $input, array $expected): void
    {
        $sortedList = $this->sllFacade->getSortedLinkedList($input);

        self::assertEquals($expected, $sortedList->getList());
    }

    /**
     * @return array<string, list<string[]>|list<int[]>>
     */
    public static function getStructures(): array
    {
        return array_merge(
            self::getIntStructures(),
            self::getStringStructures(),
        );
    }

    /**
     * @return array<string, list<int[]>>
     */
    public static function getIntStructures(): array
    {
        return [
            'singleInt' => [
                [1],
                [1],
            ],
            'multipleInt' => [
                [5, 3, 8, 1, 2],
                [1, 2, 3, 5, 8],
            ],
            'equalInt' => [
                [5, 5, 5, 5, 5],
                [5, 5, 5, 5, 5],
            ],
            'alreadySortedInt' => [
                [1, 2, 3, 5, 8],
                [1, 2, 3, 5, 8],
            ],
            'reverseSortedInt' => [
                [8, 5, 3, 2, 1],
                [1, 2, 3, 5, 8],
            ],
        ];
    }

    /**
     * @return array<string, list<string[]>>
     */
    public static function getStringStructures(): array
    {
        return [
            'emptyString' => [
                [''],
                [''],
            ],
            'singleString' => [
                ['a'],
                ['a'],
            ],
            'multipleString' => [
                ['e', 'c', 'b', 'a', 'd'],
                ['a', 'b', 'c', 'd', 'e'],
            ],
            'equalString' => [
                ['c', 'c', 'c', 'c', 'c'],
                ['c', 'c', 'c', 'c', 'c'],
            ],
            'words' => [
                ['apple', 'durian', 'cherry', 'banana'],
                ['apple', 'banana', 'cherry', 'durian'],
            ],
            'names' => [
                ['Alice', 'Charlie', 'Anna', 'Bob'],
                ['Alice', 'Anna', 'Bob', 'Charlie'],
            ],
        ];
    }

    /**
     * @dataProvider getInvalidStructures
     * @param list<string|int> $input
     */
    public function testException(array $input, string $expected): void
    {
        try {
            $this->sllFacade->getSortedLinkedList($input);
            self::fail('Expected InvalidArgumentException not thrown');
        } catch (InvalidArgumentException $e) {
            self::assertSame($expected, $e->getMessage());
            return;
        }
    }

    /**
     * @return array<string, list<mixed>>
     */
    public static function getInvalidStructures(): array
    {
        return [
            'mixedTypes1' => [
                [1, 'two', 3],
                'All items must be integers.',
            ],
            'mixedTypes2' => [
                ['one', 2, 'three'],
                'All items must be strings.',
            ],
            'emptyArray' => [
                [],
                'Unsupported data type. Only integers and strings are allowed.',
            ],
            'nullValue' => [
                [null],
                'Unsupported data type. Only integers and strings are allowed.',
            ],
            'nonScalarValues' => [
                [[1, 2], [3, 4]],
                'Unsupported data type. Only integers and strings are allowed.',
            ],
        ];
    }

    public function testAddIntNode(): void
    {
        $list = $this->sllFacade->getSortedLinkedList([3, 9, 6]);
        self::assertInstanceOf(SortedLinkedIntList::class, $list, 'Failed to create SortedLinkedIntList instance');

        $list->add(4);
        self::assertEquals([3, 4, 6, 9], $list->getList(), 'Failed to insert new node to the middle');

        $list->add(1);
        self::assertEquals([1, 3, 4, 6, 9], $list->getList(), 'Failed to insert new node to the beginning');

        $list->add(10);
        self::assertEquals([1, 3, 4, 6, 9, 10], $list->getList(), 'Failed to insert new node to the end');
    }

    public function testAddStringNode(): void
    {
        $list = $this->sllFacade->getSortedLinkedList(['c', 'f', 'd']);
        self::assertInstanceOf(SortedLinkedStringList::class, $list, 'Failed to create SortedLinkedIntList instance');

        $list->add('e');
        self::assertEquals(['c', 'd', 'e', 'f'], $list->getList(), 'Failed to insert new node to the middle');

        $list->add('a');
        self::assertEquals(['a', 'c', 'd', 'e', 'f'], $list->getList(), 'Failed to insert new node to the beginning');

        $list->add('g');
        self::assertEquals(['a', 'c', 'd', 'e', 'f', 'g'], $list->getList(), 'Failed to insert new node to the end');
    }

    public function testSuccessRemoveIntNode(): void
    {
        $list = $this->sllFacade->getSortedLinkedList([6, 4, 3, 10, 9, 1]);
        self::assertInstanceOf(SortedLinkedIntList::class, $list, 'Failed to create SortedLinkedIntList instance');
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
        $list = $this->sllFacade->getSortedLinkedList([3, 9, 6]);
        self::assertInstanceOf(SortedLinkedIntList::class, $list, 'Failed to create SortedLinkedIntList instance');
        self::assertEquals([3, 6, 9], $list->getList(), 'Failed to create initial list');

        $otherList = $this->sllFacade->getSortedLinkedList([3, 9, 6]);
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

    public function testSuccessfulRemoveStringNode(): void
    {
        $list = $this->sllFacade->getSortedLinkedList(['c', 'e', 'f', 'a', 'd', 'g']);
        self::assertInstanceOf(SortedLinkedStringList::class, $list, 'Failed to create SortedLinkedIntList instance');
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
        $list = $this->sllFacade->getSortedLinkedList(['e', 'c', 'f']);
        self::assertInstanceOf(SortedLinkedStringList::class, $list, 'Failed to create SortedLinkedIntList instance');
        self::assertEquals(['c', 'e', 'f'], $list->getList(), 'Failed to create initial list');

        $otherList = $this->sllFacade->getSortedLinkedList(['e', 'c', 'f']);
        self::assertInstanceOf(SortedLinkedStringList::class, $otherList, 'Failed to create SortedLinkedIntList instance');
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
