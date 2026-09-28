<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'consent' => ['accepted'],
            'source' => ['nullable', 'string', 'max:120'],
        ], [
            'email.required' => 'Indique um email válido.',
            'email.email' => 'O email não parece válido.',
            'consent.accepted' => 'É necessário o seu consentimento para subscrever.',
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => mb_strtolower($data['email'])],
            [
                'status' => 'pending',
                'source' => $data['source'] ?? 'site',
                'consented_at' => now(),
            ],
        );

        return back(303)->with('newsletter_status', 'Obrigado! Confirme a subscrição no email que enviámos.');
    }
}
