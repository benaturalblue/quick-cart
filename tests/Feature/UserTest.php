<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private function validUserData(array $overrides = []): array
    {
        $user = User::factory()->make($overrides);

        return array_merge($user->toArray(), [
            'password' => $overrides['password'] ?? 'password123',
            'password_confirmation' => $overrides['password_confirmation'] ?? 'password123',
        ]);
    }

    /** @test */public function ユーザーは正常に新規登録できる()
    {
        $userData = User::factory()->make([
            'name' => 'テストユーザー',
            'number' => '09012345678',
        ])->toArray();

        $userData['password'] = 'password123';
        $userData['password_confirmation'] = 'password123';

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/home');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    /** @test */public function 登録済みユーザーは正常にログインできる()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */public function ログイン中のユーザーはログアウトできる()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */ public function 名前が未入力だと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['name' => '']));
        $response->assertSessionHasErrors('name');
    }

    /** @test */ public function ニックネームが未入力だと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['nickname' => '']));
        $response->assertSessionHasErrors('nickname');
    }

    /** @test */ public function メールアドレスが未入力だと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['email' => '']));
        $response->assertSessionHasErrors('email');
    }

    /** @test */ public function 住所が未入力だと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['address' => '']));
        $response->assertSessionHasErrors('address');
    }

    /** @test */ public function 電話番号が未入力だと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['number' => '']));
        $response->assertSessionHasErrors('number');
    }

    /** @test */ public function パスワードが未入力だと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['password' => '', 'password_confirmation' => '']));
        $response->assertSessionHasErrors('password');
    }

    /** @test */ public function 確認用パスワードが未入力だと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['password_confirmation' => '']));
        $response->assertSessionHasErrors('password');
    }

    /** @test */ public function パスワードが8文字未満だと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['password' => 'short', 'password_confirmation' => 'short']));
        $response->assertSessionHasErrors('password');
    }

    /** @test */ public function パスワード確認が一致しないと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['password' => 'password123', 'password_confirmation' => 'password456']));
        $response->assertSessionHasErrors('password');
    }

    /** @test */ public function メールアドレスにアットマークがないと登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['email' => 'invalid-email']));
        $response->assertSessionHasErrors('email');
    }

    /** @test */ public function すでに登録されたメールアドレスでは登録できない()
    {
        $existing = User::factory()->create(['email' => 'test@example.com']);
        $response = $this->post('/register', $this->validUserData(['email' => 'test@example.com']));
        $response->assertSessionHasErrors('email');
    }

    /** @test */ public function 電話番号に半角数字以外があると登録できない()
    {
        $response = $this->post('/register', $this->validUserData(['number' => '090-abc-1234']));
        $response->assertSessionHasErrors('number');
    }

    /** @test */ public function 登録されていないメールアドレスではログインできない()
    {
        $existing = User::factory()->create(['email' => 'test@example.com']);
        $response = $this->post('/login', $this->validUserData(['email' => 'logintest@example.com']));
        $response->assertSessionHasErrors('email');
    }

    /** @test */ public function 登録されていないパスワードではログインできない()
    {
        $existing = User::factory()->create(['password' => 'password123']);
        $response = $this->post('/login', $this->validUserData(['password' => 'password456']));
        $response->assertSessionHasErrors('email');
    }
}
