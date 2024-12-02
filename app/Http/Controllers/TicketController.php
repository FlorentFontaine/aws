<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $apiKey = 'votre_cle_api_redmine'; // clé API Redmine
        $redmineUrl = 'https://votre_instance_redmine_url'; // URL instance Redmine

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Redmine-API-Key' => $apiKey,
        ])->post("$redmineUrl/issues.json", [
            'issue' => [
                'project_id' => 1, // ID du projet
                'subject' => $request->subject,
                'description' => $request->description,
            ]
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Ticket créé avec succès!');
        } else {
            return back()->with('error', 'Erreur lors de la création du ticket. Code HTTP: ' . $response->status());
        }
    }
}
