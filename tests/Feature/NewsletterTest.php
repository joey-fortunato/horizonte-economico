<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribe_with_consent(): void
    {
        $this->post('/newsletter', [
            'email' => 'leitor@exemplo.co.ao',
            'consent' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'leitor@exemplo.co.ao',
        ]);
    }

    public function test_consent_is_required(): void
    {
        $this->post('/newsletter', [
            'email' => 'leitor@exemplo.co.ao',
        ])->assertSessionHasErrors('consent');

        $this->assertDatabaseMissing('newsletter_subscribers', [
            'email' => 'leitor@exemplo.co.ao',
        ]);
    }

    public function test_email_is_validated(): void
    {
        $this->post('/newsletter', [
            'email' => 'invalido',
            'consent' => '1',
        ])->assertSessionHasErrors('email');
    }
}
