<?php

namespace Spatie\ElasticSearchQueryBuilder\Builder\Aggregations;

use Spatie\ElasticSearchQueryBuilder\Builder\AggregationCollection;
use Spatie\ElasticSearchQueryBuilder\Builder\Aggregations\Concerns\WithAggregations;
use Spatie\ElasticSearchQueryBuilder\Builder\Aggregations\Concerns\WithMissing;

class TermsAggregation extends Aggregation
{
    use WithMissing, WithAggregations;

    protected string $field;

    protected ?int $size = null;

    protected ?array $metaData = null;

    protected ?array $order = null;

    public static function create(string $name, string $field): self
    {
        return new self($name, $field);
    }

    public function __construct(string $name, string $field)
    {
        $this->name = $name;
        $this->field = $field;
        $this->aggregations = new AggregationCollection();
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function metaData(array $metaData): self
    {
        $this->metaData = $metaData;

        return $this;
    }

    public function order(array $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function toArray(): array
    {
        $parameters = [
            'field' => $this->field,
        ];

        if ($this->size) {
            $parameters['size'] = $this->size;
        }

        if ($this->missing) {
            $parameters['missing'] = $this->missing;
        }

        if ($this->order) {
            $parameters['order'] = $this->order;
        }

        $aggregation = [
            'terms' => $parameters,
        ];

        if (! $this->aggregations->isEmpty()) {
            $aggregation['aggs'] = $this->aggregations->toArray();
        }

        if ($this->metaData !== null) {
            $aggregation['meta'] = $this->metaData;
        }

        return $aggregation;
    }
}
