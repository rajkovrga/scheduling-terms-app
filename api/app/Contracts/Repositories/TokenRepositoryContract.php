<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

use SchedulingTerms\App\Dto\Tokens\CreateTokenDto;
use SchedulingTerms\App\Models\Token;

interface TokenRepositoryContract
{
    public const PER_PAGE = 10;
    public function get(string $token): Token;
    public function getByUserId(int $userId): Token;
    public function paginate(int $perPage = self::PER_PAGE): array;
    public function delete(string $token): void;
    public function create(CreateTokenDto $tokenDto): Token;
}