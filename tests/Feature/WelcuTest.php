<?php

namespace Tests\Feature\Admin;

use App\Application;
use App\Enums\WelcuEventType;
use Tests\TestCase;

class WelcuTest extends TestCase
{
    /** @test */
    public function wrong_payload_will_not_trigger_event()
    {
        $this->postJson(route('welcu.process'))
            ->assertStatus(422);

        $this->postJson(route('welcu.process'), [
            'action_type' => WelcuEventType::NewSale,
        ])->assertStatus(422);

        $this->postJson(route('welcu.process'), [
            'action_type' => WelcuEventType::NewSale,
            'sale' => [],
        ])->assertStatus(422);

        $this->postJson(route('welcu.process'), [
            'action_type' => WelcuEventType::NewSale,
            'sale' => [
                'buyer' => []
            ],
        ])->assertStatus(422);

        $this->postJson(route('welcu.process'), [
            'action_type' => WelcuEventType::NewSale,
            'sale' => [
                'buyer' => [
                    'email' => 'tests@test.com'
                ]
            ],
        ])->assertStatus(422);

        $this->postJson(route('welcu.process'), [
            'action_type' => WelcuEventType::NewSale,
            'sale' => [
                'buyer' => [
                    'email' => 'tests@test.com',
                    'first_name' => 'Test'
                ]
            ],
        ])->assertStatus(422);
    }

    /** @test */
    public function wrong_event_type_will_not_trigger_event()
    {
        $this->postJson(route('welcu.process'), [
            'action_type' => 'test',
            'sale' => [
                'buyer' => [
                    'email' => 'tests@test.com',
                    'first_name' => 'Test',
                    'last_name' => 'Test'
                ]
            ],
        ])->assertStatus(401);
    }

    /** @test */
    public function good_event_payload_will_trigger()
    {
        $this->postJson(route('welcu.process'), [
            'action_type' => WelcuEventType::NewSale,
            'sale' => [
                'buyer' => [
                    'email' => 'tests@test.com',
                    'first_name' => 'Test',
                    'last_name' => 'Test'
                ]
            ],
        ])->assertStatus(200);
    }
}
