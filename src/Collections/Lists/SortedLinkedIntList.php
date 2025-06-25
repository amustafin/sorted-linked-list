<?php

declare(strict_types=1);

namespace Mustafin\SLL\Collections\Lists;

use InvalidArgumentException;
use Mustafin\SLL\Collections\Nodes\IntNode;
use Mustafin\SLL\Collections\Nodes\Node;

class SortedLinkedIntList extends SortedLinkedList
{
    private ?IntNode $head = null;

    /**
     * @param int[] $data
     */
    private function __construct(array $data)
    {
        if (count($data) === 0) {
            return;
        }
        $this->salt = random_int(1000000, 9999999);
        $current = $this->getHead();
        foreach ($data as $value) {
            $newItem = new IntNode($this->prepareHash($value), $value);
            if ($current === null) {
                $this->setHead($newItem);
            } else {
                $newItem->setPrevious($current);
                $current->setNext($newItem);
            }
            $current = $newItem;
        }
    }

    /**
     * @return IntNode|null
     */
    public function getHead(): ?Node
    {
        return $this->head;
    }

    public function add(int $new): void
    {
        $newNode = new IntNode($this->prepareHash($new), $new);
        $this->addNode($newNode, $this->getHead());
    }

    public function remove(IntNode $originalNode): void
    {
        $node = new IntNode(
            $this->prepareHash($originalNode->getValue()),
            $originalNode->getValue()
        );
        if ($node->hash !== $originalNode->hash) {
            throw new InvalidArgumentException("Node doesn't belong to current list");
        }
        $this->removeNode($originalNode);
    }

    /**
     * @param int[] $data
     */
    public static function create(array $data): self
    {
        sort($data);
        return new self($data);
    }

    /**
     * @param ?IntNode $item
     */
    protected function setHead(?Node $item): void
    {
        $this->head = $item;
    }
}
