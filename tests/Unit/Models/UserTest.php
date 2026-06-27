<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_traits_autenticacao()
    {
        $traits = class_uses_recursive(User::class);

        $this->assertContains(HasApiTokens::class, $traits);
        $this->assertContains(HasFactory::class, $traits);
        $this->assertContains(Notifiable::class, $traits);
    }

    public function test_fillable_usuario()
    {
        $user = new User();

        $this->assertSame([
            'name',
            'login',
            'email',
            'password',
            'situacao',
        ], $user->getFillable());
    }

    public function test_login_fillable()
    {
        $user = new User();

        $this->assertTrue($user->isFillable('login'));
    }

    public function test_situacao_fillable()
    {
        $user = new User();

        $this->assertTrue($user->isFillable('situacao'));
    }

    public function test_hidden_usuario()
    {
        $user = new User();

        $this->assertSame([
            'password',
            'remember_token',
        ], $user->getHidden());
    }

    public function test_oculta_dados_sensiveis()
    {
        $user = new User([
            'name' => 'Maria',
            'login' => 'maria',
            'email' => 'maria@example.com',
            'password' => 'secret',
        ]);

        $user->setAttribute('remember_token', 'token-123');

        $data = $user->toArray();

        $this->assertArrayNotHasKey('password', $data);
        $this->assertArrayNotHasKey('remember_token', $data);
    }

    public function test_cast_email_verified_at()
    {
        $user = new User();

        $this->assertSame('datetime', $user->getCasts()['email_verified_at']);
    }
}
