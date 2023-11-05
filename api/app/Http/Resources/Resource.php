<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources;

use Generator;
use JsonSerializable;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @template T
 */
abstract class Resource
{
    /**
     * @var T
     */
    protected $item;
    
    /**
     * @param T $item
     */
    public function __construct($item)
    {
        $this->item = $item;
    }
    
    public abstract function toArray(Request $request): array;
    
    public static function toCollection(iterable $items): Generator
    {
        foreach ($items as $item) {
            yield static::toArray($item);
        }
    }
}