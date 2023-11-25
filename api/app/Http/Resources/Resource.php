<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources;

use Generator;
use Psr\Http\Message\ServerRequestInterface;

abstract class Resource
{

    protected ServerRequestInterface $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
    
    public abstract function toArray($item): array;
    
    public function toCollection(iterable $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $this->toArray($item);
        }
        return $result;
    }
}