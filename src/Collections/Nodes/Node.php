<?php

declare(strict_types=1);

namespace Mustafin\SLL\Collections\Nodes;

abstract class Node
{
    protected ?Node $next = null;

    protected ?Node $previous = null;

    public function __construct(
        public readonly string $hash,
    ) {
    }

    abstract public function create(int|string $value, string $hash): self;

    abstract public function getValue(): int|string;

    public function getNext(): ?self
    {
        return $this->next;
    }

    public function setNext(?self $next): void
    {
        $this->next = $next;
    }

    public function getPrevious(): ?self
    {
        return $this->previous;
    }

    public function setPrevious(?self $previous): void
    {
        $this->previous = $previous;
    }

    public function eq(self $item): bool
    {
        return $this->compare($item) === 0
            && $this->hash === $item->hash;
    }

    public function gt(self $item): bool
    {
        return $this->compare($item) > 0;
    }

    public function lt(self $item): bool
    {
        return $this->compare($item) < 0;
    }

    public function ge(self $item): bool
    {
        return $this->compare($item) >= 0;
    }

    public function le(self $item): bool
    {
        return $this->compare($item) <= 0;
    }

    private function compare(self $item): int
    {
        return $this->getValue() <=> $item->getValue();
    }
}
