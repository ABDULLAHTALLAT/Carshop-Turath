<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_load(): void
    {
        foreach (['/', '/about', '/shop', '/contact', '/login'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_home_uses_the_single_hero_video(): void
    {
        $this->get('/')
            ->assertSee('media/hero.mp4', false)
            ->assertDontSee('media/drive-1.mp4', false);
    }

    public function test_shop_filters_open_in_a_side_panel(): void
    {
        $this->get('/shop')
            ->assertSee('data-fdrawer', false)
            ->assertSee('data-fdrawer-open', false);
    }

    public function test_colour_swatches_carry_their_real_colour(): void
    {
        $this->get('/shop')->assertSee('data-bg="#c0262d"', false);
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get('/')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Content-Security-Policy');
    }

    public function test_contact_form_stores_a_message(): void
    {
        $this->post('/contact', [
            'name' => 'Ahmed',
            'email' => 'ahmed@example.com',
            'subject' => 'Hello',
            'message' => 'I would like to know more about your cars.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('inquiries', ['email' => 'ahmed@example.com']);
    }

    public function test_honeypot_blocks_bots(): void
    {
        $this->post('/contact', [
            'name' => 'Bot', 'email' => 'bot@example.com', 'subject' => 'Spam',
            'message' => 'Buy cheap stuff right now please.', 'website' => 'http://spam.test',
        ]);

        $this->assertDatabaseCount('inquiries', 0);
    }
}
