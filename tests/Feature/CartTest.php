<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログインユーザーが商品をカートに追加できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post('/cart/add', [
                'item_id' => $item->id,
                'quantity' => 1,
            ]);

        $response->assertRedirect('/cart');

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'quantity' => 1,
        ]);
    }

    /** @test */
    public function 未ログインではカートに追加できずログインページへ遷移する()
    {
        $item = Item::factory()->create();

        $response = $this->post('/cart/add', [
            'item_id' => $item->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('cart_items', [
            'item_id' => $item->id,
        ]);
    }
}
