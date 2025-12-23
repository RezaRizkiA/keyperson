<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ClientController extends Controller
{
    protected ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display paginated list of clients with stats
     * Only accessible by administrators
     *
     * @param Request $request
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            $data = $this->clientService->getClientsWithStats($request);

            return Inertia::render('Administrator/Clients/Index', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load clients: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'error' => 'Failed to load clients. Please try again later.'
            ]);
        }
    }

    /**
     * Show client detail
     *
     * @param int $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        try {
            $data = $this->clientService->getClientDetail($id);

            return Inertia::render('Administrator/Clients/Show', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load client detail: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard.clients.index')
                ->withErrors(['error' => 'Client not found.']);
        }
    }

    /**
     * Show edit form for client
     *
     * @param int $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            $data = $this->clientService->getClientForEdit($id);
            
            // Get all categories with subcategories and skills (hierarchical)
            $data['categories'] = Category::with(['subCategories.skills'])
                ->select('id', 'name')
                ->get();
            
            return Inertia::render('Administrator/Clients/Edit', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load client for edit: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard.clients.index')
                ->withErrors(['error' => 'Client not found.']);
        }
    }

    /**
     * Update client data
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'employee_count' => 'nullable|string|max:50',
            'about' => 'nullable|string',
            'selected_skills' => 'nullable|array',
            'selected_skills.*' => 'exists:skills,id',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            $this->clientService->updateClient($id, $request->all());

            return redirect()->route('dashboard.clients.show', $id)
                ->with('success', 'Client updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update client: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'Failed to update client. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Delete client
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        try {
            // Get client info before deletion for flash message
            $client = \App\Models\Client::with('user')->findOrFail($id);
            $companyName = $client->company_name;
            $clientId = '#CL-' . str_pad($id, 3, '0', STR_PAD_LEFT);
            
            $this->clientService->deleteClient($id);

            return redirect()->route('dashboard.clients.index')
                ->with('success', "The client {$companyName} ({$clientId}) has been permanently removed from the system.");
        } catch (\Exception $e) {
            Log::error('Failed to delete client: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'error' => 'Failed to delete client. Please try again.'
            ]);
        }
    }
}
