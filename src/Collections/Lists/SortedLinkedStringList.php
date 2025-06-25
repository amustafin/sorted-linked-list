<?php

declare(strict_types=1);

namespace Mustafin\SLL\Collections\Lists;

use InvalidArgumentException;
use Mustafin\SLL\Collections\Nodes\Node;
use Mustafin\SLL\Collections\Nodes\StringNode;

class SortedLinkedStringList extends SortedLinkedList
{
    private ?StringNode $head = null;

    /**
     * @param string[] $data
     */
    private function __construct(
        array $data,
    ) {
        if (count($data) === 0) {
            return;
        }

        $this->salt = random_int(1000000, 9999999);
        $current = $this->getHead();
        foreach ($data as $value) {
            $newItem = new StringNode($this->prepareHash($value), $value);
            if ($current === null) {
                $this->setHead($newItem);
            } else {
                $newItem->setPrevious($current);
                $current->setNext($newItem);
            }
            $current = $newItem;
        }
    }

    public function add(string $new): void
    {
        $newNode = new StringNode($this->prepareHash($new), $new);
        $this->addNode($newNode, $this->getHead());
    }

    public function remove(StringNode $originalNode): void
    {
        $node = new StringNode(
            $this->prepareHash($originalNode->getValue()),
            $originalNode->getValue()
        );
        if ($node->hash !== $originalNode->hash) {
            throw new InvalidArgumentException("Node doesn't belong to current list");
        }
        $this->removeNode($originalNode);
    }

    /**
     * @param string[] $data
     */
    public static function create(array $data): self
    {
        sort($data);

        return new self($data);
    }

    /**
     * @return StringNode|null
     */
    public function getHead(): ?Node
    {
        return $this->head;
    }

    /**
     * @param ?StringNode $item
     */
    protected function setHead(?Node $item): void
    {
        $this->head = $item;
    }
}
