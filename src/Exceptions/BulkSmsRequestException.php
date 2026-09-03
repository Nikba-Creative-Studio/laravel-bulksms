<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Exceptions;

use Illuminate\Http\Client\Response;
use Throwable;

/**
 * Thrown when the BulkSMS API responds with a non-successful status code.
 *
 * The decoded response body is preserved so callers can inspect the API's own
 * error details (for example, insufficient credits on a 403).
 */
class BulkSmsRequestException extends BulkSmsException
{
    /**
     * @param  array<string, mixed>  $errors  The decoded API error payload.
     */
    public function __construct(
        string $message,
        public readonly int $status,
        public readonly array $errors = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $status, $previous);
    }

    public static function fromResponse(Response $response): self
    {
        $body = $response->json();
        $body = is_array($body) ? $body : [];

        $message = self::extractMessage($body)
            ?? sprintf('BulkSMS request failed with HTTP status %d.', $response->status());

        return new self(
            message: $message,
            status: $response->status(),
            errors: $body,
        );
    }

    /**
     * @param  array<string, mixed>  $body
     */
    private static function extractMessage(array $body): ?string
    {
        // The API returns { "title": "...", "detail": "..." } style errors.
        foreach (['detail', 'title', 'message'] as $key) {
            if (isset($body[$key]) && is_string($body[$key]) && $body[$key] !== '') {
                return $body[$key];
            }
        }

        return null;
    }
}
