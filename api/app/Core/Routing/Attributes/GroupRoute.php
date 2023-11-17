<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Core\Routing\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class GroupRoute
{
    public function __construct(
        public readonly string $path,
        public readonly array $middlewares = []
    ) {
    }
}