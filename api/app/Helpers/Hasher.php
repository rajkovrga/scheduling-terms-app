<?php

namespace SchedulingTerms\App\Helpers;

use Exception;

class Hasher
{
    public function hashPassword(string $value): string {
        $options = [
            'memory_cost' => 1 << 17, // 128 MB
            'time_cost'   => 4,
            'threads'     => 2,
        ];
        return password_hash($value, PASSWORD_ARGON2I, $options);
    }

    public function hashToken(): string {
        return hash('sha512/256', base64_decode(random_bytes(8)));
    }

    /**
     * @throws Exception
     */
    public function randomPassword(): string {
        return base64_decode(random_bytes(10));
    }
}