<?php

declare(strict_types=1);

namespace lox24\api_client;

use JsonSerializable;
use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use Stringable;

abstract class ListFilter implements Stringable, JsonSerializable
{

    private array $params = [];

    abstract protected function isFieldExists(string $field): bool;

    abstract protected function allowedOrderingFields(): array;

    abstract protected function allowedRangeFields(): array;

    abstract protected function allowedBooleanFields(): array;

    abstract protected function allowedSearchFields(): array;

    abstract protected function allowedExistFields(): array;


    /**
     * @throws ApiException|ClientException
     */
    public function orderBy(string $field, ListFilterOrdering $ordering = ListFilterOrdering::ASC): self
    {
        $allowed = $this->allowedOrderingFields();
        if (!$this->isFieldAllowed($field, $allowed)) {
            ClientException::throw("Invalid ordering field $field.");
        }

        $this->params["_order"][$field] ??= [];
        $this->params["_order"][$field] = $ordering->value;

        return $this;
    }

    public function removeOrderBy(string $field): self
    {
        unset($this->params["_order"][$field]);

        $this->cleanUpEmptyParam('_order');

        return $this;
    }


    /**
     * @throws ApiException|ClientException
     */
    public function between(string $field, string|int|float $from, string|int|float $to): self
    {
        $allowed = $this->allowedRangeFields();
        if (!$this->isFieldAllowed($field, $allowed)) {
            ClientException::throw("Invalid range field $field.");
        }

        $this->params[$field] ??= [];
        $this->params[$field]['between'] = "$from..$to";

        return $this;
    }

    public function removeBetween(string $field): self
    {
        unset($this->params[$field]['between']);
        $this->cleanUpEmptyParam($field);

        return $this;
    }

    /**
     * @throws ApiException|ClientException
     */
    public function greater(string $field, string|int|float|null $value): self
    {
        return $this->setRangeCondition($field, 'gt', $value);
    }

    public function removeGreater(string $field): self
    {
        return $this->unsetRangeCondition($field, 'gt');
    }

    /**
     * @throws ApiException|ClientException
     */
    public function greaterOrEqual(string $field, string|int|float|null $value): self
    {
        return $this->setRangeCondition($field, 'gte', $value);
    }

    public function removeGreaterOrEqual(string $field): self
    {
        return $this->unsetRangeCondition($field, 'gte');
    }

    /**
     * @throws ApiException|ClientException
     */
    public function less(string $field, string|int|float|null $value): self
    {
        return $this->setRangeCondition($field, 'lt', $value);
    }

    public function removeLess(string $field): self
    {
        return $this->unsetRangeCondition($field, 'lt');
    }

    /**
     * @throws ApiException|ClientException
     */
    public function lessOrEqual(string $field, string|int|float|null $value): self
    {
        return $this->setRangeCondition($field, 'lte', $value);
    }

    public function removeLessOrEqual(string $field): self
    {
        return $this->unsetRangeCondition($field, 'lte');
    }

    /**
     * @throws ApiException|ClientException
     */
    public function search(string $field, string|int|float|null $value): self
    {
        if (!$this->isFieldAllowed($field, $this->allowedSearchFields())) {
            ClientException::throw("Invalid search field $field");
        }

        $this->params[$field] ??= [];
        $this->params[$field][] = $value;

        return $this;
    }

    /**
     * @throws ApiException|ClientException
     */
    public function removeSearch(string $field, mixed $value = null): self
    {
        if (!$this->isFieldAllowed($field, $this->allowedSearchFields())) {
            ClientException::throw("Invalid search field $field");
        }

        if ($value === null) {
            unset($this->params[$field]);

            return $this;
        }

        $key = array_search($value, $this->params[$field] ?? [], true);
        if ($key !== false) {
            unset($this->params[$field][$key]);
        }

        $this->cleanUpEmptyParam($field);

        return $this;
    }


    /**
     * @throws ApiException|ClientException
     */
    public function boolean(string $field, bool $value): self
    {
        if (!$this->isFieldAllowed($field, $this->allowedBooleanFields())) {
            ClientException::throw("Invalid boolean field $field");
        }

        $this->params[$field] = (int)$value;

        return $this;
    }

    public function removeBoolean(string $field): self
    {
        unset($this->params[$field]);
        $this->cleanUpEmptyParam($field);

        return $this;
    }

    /**
     * @throws ApiException|ClientException
     */
    public function exist(string $field): self
    {
        if (!$this->isFieldAllowed($field, $this->allowedExistFields())) {
            ClientException::throw("Invalid 'exists' field $field");
        }

        $this->params['exists'] ??= [];
        $this->params['exists'][$field] = 1;

        return $this;
    }

    /**
     * @throws ApiException|ClientException
     */
    public function notExist(string $field): self
    {
        if (!$this->isFieldAllowed($field, $this->allowedExistFields())) {
            ClientException::throw("Invalid 'exists' field $field");
        }

        $this->params['exists'] ??= [];
        $this->params['exists'][$field] = 0;

        return $this;
    }

    public function removeExist(): self
    {
        unset($this->params['exists']);

        return $this;
    }


    /**
     * @throws ApiException|ClientException
     */
    private function setRangeCondition(
        string           $field,
        string           $type,
        string|int|float $value
    ): self {
        $allowed = $this->allowedRangeFields();
        if (!in_array($field, $allowed, true)) {
            ClientException::throw("Invalid range field $field.");
        }

        $this->params[$field] ??= [];
        $this->params[$field][$type] = $value;

        return $this;
    }

    private function unsetRangeCondition(
        string $field,
        string $type
    ): self {
        unset($this->params[$field][$type]);
        $this->cleanUpEmptyParam($field);

        return $this;
    }


    public function jsonSerialize(): array
    {
        return $this->params;
    }


    public function __toString(): string
    {
        return http_build_query($this->params);
    }

    private function cleanUpEmptyParam(string $name): void
    {
        if (empty($this->params[$name])) {
            unset($this->params[$name]);
        }
    }

    private function isFieldAllowed(string $field, array $allowed): bool
    {
        return $this->isFieldExists($field) && in_array($field, $allowed, true);
    }
}