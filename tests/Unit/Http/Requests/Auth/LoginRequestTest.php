<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    public function test_authorize()
    {
        $request = $this->makeRequest();

        $this->assertTrue($request->authorize());
    }

    public function test_login_senha()
    {
        $request = $this->makeRequest();

        $this->assertSame([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], $request->rules());
    }

    public function test_throttle_key_login_ip()
    {
        $request = $this->makeRequest([
            'login' => 'CheF.Admin',
        ], '10.0.0.1');

        $this->assertSame('chef.admin|10.0.0.1', $request->throttleKey());
    }

    public function test_throttle_key_ignora_email()
    {
        $request = $this->makeRequest([
            'login' => 'Confeiteira',
            'email' => 'outro@example.com',
        ], '192.168.0.15');

        $this->assertSame('confeiteira|192.168.0.15', $request->throttleKey());
    }

    public function test_rate_limit_disponivel()
    {
        $request = $this->makeRequest([
            'login' => 'chef',
        ]);

        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->with('chef|127.0.0.1', 5)
            ->andReturnFalse();

        $this->assertNull($request->ensureIsNotRateLimited());
    }

    public function test_rate_limit_bloqueado()
    {
        Event::fake([Lockout::class]);

        $request = $this->makeRequest([
            'login' => 'chef',
        ]);

        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->with('chef|127.0.0.1', 5)
            ->andReturnTrue();

        RateLimiter::shouldReceive('availableIn')
            ->once()
            ->with('chef|127.0.0.1')
            ->andReturn(120);

        try {
            $request->ensureIsNotRateLimited();
            $this->fail('ValidationException was not thrown.');
        } catch (ValidationException $exception) {
            $this->assertSame([
                'login' => [trans('auth.throttle', ['seconds' => 120, 'minutes' => 2])],
            ], $exception->errors());
        }

        Event::assertDispatched(Lockout::class);
    }

    public function test_authenticate_sucesso()
    {
        $request = $this->makeRequest([
            'login' => 'chef',
            'password' => 'secret',
            'remember' => '1',
        ]);

        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->with('chef|127.0.0.1', 5)
            ->andReturnFalse();

        Auth::shouldReceive('attempt')
            ->once()
            ->with([
                'login' => 'chef',
                'password' => 'secret',
            ], true)
            ->andReturnTrue();

        RateLimiter::shouldReceive('clear')
            ->once()
            ->with('chef|127.0.0.1');

        $request->authenticate();

        $this->assertTrue(true);
    }

    public function test_authenticate_falha()
    {
        $request = $this->makeRequest([
            'login' => 'chef',
            'password' => 'wrong-password',
        ]);

        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->with('chef|127.0.0.1', 5)
            ->andReturnFalse();

        Auth::shouldReceive('attempt')
            ->once()
            ->with([
                'login' => 'chef',
                'password' => 'wrong-password',
            ], false)
            ->andReturnFalse();

        RateLimiter::shouldReceive('hit')
            ->once()
            ->with('chef|127.0.0.1');

        try {
            $request->authenticate();
            $this->fail('ValidationException was not thrown.');
        } catch (ValidationException $exception) {
            $this->assertSame([
                'login' => [trans('auth.failed')],
            ], $exception->errors());
        }
    }

    private function makeRequest(array $input = [], string $ip = '127.0.0.1'): LoginRequest
    {
        $baseRequest = Request::create('/login', 'POST', $input, [], [], [
            'REMOTE_ADDR' => $ip,
        ]);

        return LoginRequest::createFromBase($baseRequest);
    }
}
