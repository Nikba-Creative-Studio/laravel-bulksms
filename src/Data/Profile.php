<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * An immutable representation of the account profile returned by GET /profile.
 *
 * @implements Arrayable<string, mixed>
 */
final readonly class Profile implements Arrayable, JsonSerializable
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public ?string $id,
        public ?string $username,
        public ?float $creditBalance,
        public ?int $quotaSize,
        public ?int $quotaRemaining,
        public array $raw = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $credits = is_array($data['credits'] ?? null) ? $data['credits'] : [];
        $quota = is_array($data['quota'] ?? null) ? $data['quota'] : [];

        return new self(
            id: isset($data['id']) ? (string) $data['id'] : null,
            username: $data['username'] ?? null,
            creditBalance: isset($credits['balance']) ? (float) $credits['balance'] : null,
            quotaSize: isset($quota['size']) ? (int) $quota['size'] : null,
            quotaRemaining: isset($quota['remaining']) ? (int) $quota['remaining'] : null,
            raw: $data,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->raw;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->raw;
    }
}
