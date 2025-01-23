<?php

declare(strict_types=1);

namespace App\Component\User;

use Symfony\Component\Serializer\Annotation\Groups;

class FullNameDto
{
    public function __construct(
        #[Groups(['user:write', 'user:read'])]
        private string $givenName,

        #[Groups(['user:write'])]
        private string $familyName,

        #[Groups(['user:write', 'user:read'])]
        private bool $isMarried
    ) {
    }

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function isMarried(): bool
    {
        return $this->isMarried;
    }

    public function getGivenName(): string
    {
        return $this->givenName;
    }


}