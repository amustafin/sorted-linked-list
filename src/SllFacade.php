<?php

declare(strict_types=1);

namespace Mustafin\SLL;

use InvalidArgumentException;
use Mustafin\SLL\Collections\Lists\SortedLinkedIntList;
use Mustafin\SLL\Collections\Lists\SortedLinkedList;
use Mustafin\SLL\Collections\Lists\SortedLinkedStringList;

class SllFacade
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param array<int|string, mixed> $array
     */
    public function getSortedLinkedList(array $array): SortedLinkedList
    {
        return match (true) {
            is_int($array[0] ?? null) => self::createIntList($array),
            is_string($array[0] ?? null) => self::createStringList($array),
            default => throw new InvalidArgumentException('Unsupported data type. Only integers and strings are allowed.')
        };
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function createIntList(array $data): SortedLinkedList
    {
        $validData = array_map(
            function ($item) {
                return is_int($item) ? $item : throw new InvalidArgumentException('All items must be integers.');
            },
            $data
        );
        return SortedLinkedIntList::create($validData);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function createStringList(array $data): SortedLinkedList
    {
        $validData = array_map(
            function ($item) {
                return is_string($item) ? $item : throw new InvalidArgumentException('All items must be strings.');
            },
            $data
        );
        return SortedLinkedStringList::create($validData);
    }
}
