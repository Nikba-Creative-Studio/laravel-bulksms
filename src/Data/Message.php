<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * An immutable representation of a BulkSMS message.
 *
 * Only the most commonly used fields are promoted to typed properties; the full
 * decoded payload is always available via {@see self::$raw}.
 *
 * @implements Arrayable<string, mixed>
 */
final readonly class Message implements Arrayable, JsonSerializable
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public ?string $id,
        public ?string $type,
        public ?string $to,
        public mixed $from,
        public ?string $body,
        public ?string $encoding,
        public ?int $numberOfParts,
        public ?float $creditCost,
        public ?string $statusType,
        public ?string $userSuppliedId,
        public ?string $submissionId,
        public array $raw = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $status = is_array($data['status'] ?? null) ? $data['status'] : [];
        $submission = is_array($data['submission'] ?? null) ? $data['submission'] : [];

        return new self(
            id: isset($data['id']) ? (string) $data['id'] : null,
            type: $data['type'] ?? null,
            to: isset($data['to']) && is_scalar($data['to']) ? (string) $data['to'] : null,
            from: $data['from'] ?? null,
            body: $data['body'] ?? null,
            encoding: $data['encoding'] ?? null,
            numberOfParts: isset($data['numberOfParts']) ? (int) $data['numberOfParts'] : null,
            creditCost: isset($data['creditCost']) ? (float) $data['creditCost'] : null,
            statusType: $status['type'] ?? null,
            userSuppliedId: $data['userSuppliedId'] ?? null,
            submissionId: isset($submission['id']) ? (string) $submission['id'] : null,
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
