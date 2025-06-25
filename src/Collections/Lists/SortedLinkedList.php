<?php

declare(strict_types=1);

namespace Mustafin\SLL\Collections\Lists;

use Mustafin\SLL\Collections\Nodes\Node;

abstract class SortedLinkedList
{
    protected int $salt;

    /**
     * @return string[]|int[]
     */
    public function getList(): array
    {
        $result = [];
        $current = $this->getHead();

        while ($current !== null) {
            $result[] = $current->getValue();
            $current = $current->getNext();
        }

        return $result;
    }

    abstract public function getHead(): ?Node;

    abstract protected function setHead(?Node $item): void;

    protected function addNode(Node $new, ?Node $current = null): void
    {
        if ($current === null) {
            $this->setHead($new);
            return;
        }

        if ($current->gt($new)) {
            $previous = $current->getPrevious();
            $new->setNext($current);

            if ($previous === null) {
                $this->setHead($new);
            } else {
                $previous->setNext($new);
                $new->setPrevious($previous);
            }

            $current->setPrevious($new);
            return;
        }

        if ($current->getNext() === null) {
            $current->setNext($new);
            $new->setPrevious($current);
        } else {
            $this->addNode($new, $current->getNext());
        }
    }

    protected function removeNode(Node $node): void
    {
        $previous = $node->getPrevious();
        $next = $node->getNext();

        if ($previous === null) {
            $this->setHead($next);
        } else {
            $previous->setNext($next);
        }

        $next?->setPrevious($previous);

        unset($node);
    }

    protected function prepareHash(int|string $value): string
    {
        return hash('sha256', strval($this->salt) . strval($value));
    }
}
