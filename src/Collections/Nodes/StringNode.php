<?php

declare(strict_types=1);

namespace Mustafin\SLL\Collections\Nodes;

class StringNode extends Node
{
    public function __construct(
        string $hash,
        private readonly string $value,
    ) {
        parent::__construct($hash);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function create(int|string $value, string $hash): self
    {
        return new self($hash, strval($value));
    }
}
