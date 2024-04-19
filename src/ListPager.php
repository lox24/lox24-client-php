<?php

declare(strict_types=1);

namespace lox24\api_client;

readonly final class ListPager
{


    public function __construct(private array $data)
    {
    }

    public function getFirst(): ?int
    {
        $value = (int)($this->data['hydra:first'] ?? null);
        return $value ?: null;
    }

    public function getCurrent(): ?int
    {
        $value = (int)($this->data['@id'] ?? null);
        return $value ?: null;
    }

    public function getLast(): ?int
    {
        $value = (int)($this->data['hydra:last'] ?? null);
        return $value ?: null;
    }

    public function getNext(): ?int
    {
        $value = (int)($this->data['hydra:next'] ?? null);
        return $value ?: null;
    }

}