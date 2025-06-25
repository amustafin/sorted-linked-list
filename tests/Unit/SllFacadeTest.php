<?php

declare(strict_types=1);

namespace Mustafin\SLL\Tests\Unit;

use InvalidArgumentException;
use Mustafin\SLL\Collections\Lists\SortedLinkedIntList;
use Mustafin\SLL\Collections\Lists\SortedLinkedStringList;
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
}
