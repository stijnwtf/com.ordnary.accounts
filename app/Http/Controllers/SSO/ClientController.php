<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function index()
    {
        $clients = Client::all();
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'redirect_uris' => 'required|string',
        ]);

        // Convert redirect URIs to array and validate each URI
        $redirectUris = preg_split('/\r\n|\r|\n/', $request->redirect_uris);
        $validUris = [];

        foreach ($redirectUris as $uri) {
            $uri = trim($uri);
            if (filter_var($uri, FILTER_VALIDATE_URL)) {
                $validUris[] = $uri;
            }
        }

        if (empty($validUris)) {
            return back()->withErrors(['redirect_uris' => 'Ten minste één geldige URI is vereist.']);
        }

        // Create new client
        Client::create([
            'name' => $request->name,
            'client_id' => Str::random(32),
            'client_secret' => Str::random(64),
            'redirect_uris' => json_encode($validUris),
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'redirect_uris' => 'required|string',
        ]);

        // Convert redirect URIs to array and validate each URI
        $redirectUris = preg_split('/\r\n|\r|\n/', $request->redirect_uris);
        $validUris = [];

        foreach ($redirectUris as $uri) {
            $uri = trim($uri);
            if (filter_var($uri, FILTER_VALIDATE_URL)) {
                $validUris[] = $uri;
            }
        }

        if (empty($validUris)) {
            return back()->withErrors(['redirect_uris' => 'Ten minste één geldige URI is vereist.']);
        }

        // Update client
        $client->update([
            'name' => $request->name,
            'redirect_uris' => json_encode($validUris),
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client succesvol verwijderd.');
    }

    /**
     * Regenerate client credentials.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerateCredentials(Client $client)
    {
        $client->update([
            'client_id' => Str::random(32),
            'client_secret' => Str::random(64),
        ]);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client credentials succesvol vernieuwd.');
    }
}
