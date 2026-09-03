<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Tests;

use Illuminate\Support\Facades\Http;
use Nikba\BulkSms\Data\Message;
use Nikba\BulkSms\Data\Profile;
use Nikba\BulkSms\Exceptions\BulkSmsRequestException;
use Nikba\BulkSms\Facades\BulkSms;
use PHPUnit\Framework\Attributes\Test;

class BulkSmsServiceTest extends TestCase
{
    #[Test]
    public function it_sends_a_simple_message(): void
    {
        Http::fake([
            'api.bulksms.com/v1/messages' => Http::response([
                ['id' => '12345', 'to' => '+447700900000', 'body' => 'Hello World'],
            ], 201),
        ]);

        $result = BulkSms::sendMessage('+447700900000', 'Hello World');

        $this->assertSame('12345', $result[0]['id']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.bulksms.com/v1/messages'
                && $request['to'] === '+447700900000'
                && $request['body'] === 'Hello World'
                && $request->hasHeader('Authorization');
        });
    }

    #[Test]
    public function it_encodes_the_token_as_basic_auth(): void
    {
        Http::fake(['*' => Http::response([], 201)]);

        BulkSms::sendMessage('+447700900000', 'Hi');

        $expected = 'Basic '.base64_encode('test-token-id:test-token-secret');

        Http::assertSent(fn ($request) => $request->header('Authorization')[0] === $expected);
    }

    #[Test]
    public function it_builds_a_message_fluently(): void
    {
        Http::fake(['*' => Http::response([['id' => '1']], 201)]);

        $messages = BulkSms::message()
            ->to('+447700900000')
            ->from('MyBrand')
            ->body('Dobrá práce!')
            ->unicode()
            ->send();

        $this->assertInstanceOf(Message::class, $messages->first());

        Http::assertSent(function ($request) {
            return $request['from'] === 'MyBrand'
                && $request['encoding'] === 'UNICODE'
                && $request['body'] === 'Dobrá práce!';
        });
    }

    #[Test]
    public function it_sends_a_batch_to_multiple_recipients(): void
    {
        Http::fake(['*' => Http::response([['id' => '1'], ['id' => '2']], 201)]);

        $messages = BulkSms::message()
            ->to(['+447700900000', '+447700900001'])
            ->body('Batch')
            ->send();

        $this->assertCount(2, $messages);

        Http::assertSent(fn ($request) => $request['to'] === ['+447700900000', '+447700900001']);
    }

    #[Test]
    public function it_retrieves_the_profile(): void
    {
        Http::fake([
            'api.bulksms.com/v1/profile' => Http::response([
                'id' => 'acc-1',
                'username' => 'nikba',
                'credits' => ['balance' => 42.5],
                'quota' => ['size' => 1000, 'remaining' => 900],
            ], 200),
        ]);

        $profile = BulkSms::profile();

        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertSame('nikba', $profile->username);
        $this->assertSame(42.5, $profile->creditBalance);
        $this->assertSame(42.5, BulkSms::credits());
    }

    #[Test]
    public function it_throws_a_request_exception_on_failure(): void
    {
        Http::fake([
            '*' => Http::response(['title' => 'Insufficient credits'], 403),
        ]);

        $this->expectException(BulkSmsRequestException::class);
        $this->expectExceptionMessage('Insufficient credits');

        try {
            BulkSms::sendMessage('+447700900000', 'Hi');
        } catch (BulkSmsRequestException $e) {
            $this->assertSame(403, $e->status);
            throw $e;
        }
    }

    #[Test]
    public function it_passes_send_query_parameters(): void
    {
        Http::fake(['*' => Http::response([['id' => '1']], 201)]);

        BulkSms::message()
            ->to('+447700900000')
            ->body('Later')
            ->autoUnicode()
            ->deduplicationId(999)
            ->send();

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'auto-unicode=true')
                && str_contains($request->url(), 'deduplication-id=999');
        });
    }
}
