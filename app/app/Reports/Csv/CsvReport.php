<?php

declare(strict_types=1);

namespace App\Reports\Csv;

use IteratorAggregate;
use Traversable;

abstract class CsvReport implements IteratorAggregate
{
    private const string EXTENSION = 'csv';

    final public function filename(): string
    {
        return static::prefix() . '_' . time() . '.' . self::EXTENSION;
    }

    public function getIterator(): Traversable
    {
        yield static::headers();
        yield from $this->rows();
    }

    abstract protected static function prefix(): string;

    abstract protected static function headers(): array;

    abstract protected function rows(): Traversable;
}
