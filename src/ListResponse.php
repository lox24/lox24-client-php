<?php

declare(strict_types=1);

namespace lox24\api_client;

readonly abstract class ListResponse extends Response
{

    public function __construct(
        private Response $response,
        private ?ListFilter $filter = null
    ) {
        parent::__construct($response->getStatus(), $response->getData());
    }

    public function getFilter(): ?ListFilter
    {
        return $this->filter;
    }

    abstract protected function getItemClass(): string;

    public function getData(): array
    {
        $itemClass = $this->getItemClass();

        return array_map(static fn(array $item) => new $itemClass(new Response(200, $item)),
            $this->response->getData()['hydra:member'] ?? []
        );
    }

    public function getTotalItems(): int
    {
        return $this->response->getData()['hydra:totalItems'] ?? 0;
    }

    public function getPager(): ListPager
    {
        return new ListPager($this->response->getData()['hydra:view']);
    }


}