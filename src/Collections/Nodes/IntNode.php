<?php

declare(strict_types=1);

namespace Mustafin\SLL\Collections\Nodes;

class IntNode extends Node
{
    public function __construct(
        string $hash,
        private readonly int $value,
    ) {
        parent::__construct($hash);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function create(int|string $value, string $hash): self
    {
        return new self($hash, intval($value));
    }
}
