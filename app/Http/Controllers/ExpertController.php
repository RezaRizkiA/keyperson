<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Services\ExpertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ExpertController extends Controller
{
    protected ExpertService $expertService;

    public function __construct(ExpertService $expertService)
    {
        $this->expertService = $expertService;
    }

    /**
     * Display paginated list of experts with stats
     * Only accessible by administrators
     *
     * @param Request $request
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            $data = $this->expertService->getExpertsWithStats($request);

            return Inertia::render('Administrator/Experts/Index', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load experts: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'error' => 'Failed to load experts. Please try again later.'
            ]);
        }
    }

    /**
     * Show expert detail
     *
     * @param int $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        try {
            $data = $this->expertService->getExpertDetail($id);

            return Inertia::render('Administrator/Experts/Show', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load expert detail: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard.experts.index')
                ->withErrors(['error' => 'Expert not found.']);
        }
    }

    /**
     * Delete expert
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        try {
            $this->expertService->deleteExpert($id);

            return redirect()->route('dashboard.experts.index')
                ->with('success', 'Expert deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete expert: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'error' => 'Failed to delete expert. Please try again.'
            ]);
        }
    }

    /**
     * Show edit form for expert
     *
     * @param int $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            $data = $this->expertService->getExpertForEdit($id);
            
            // Get all categories with subcategories and skills (hierarchical)
            $data['categories'] = \App\Models\Category::with(['subCategories.skills'])
                ->select('id', 'name')
                ->get();
            
            return Inertia::render('Administrator/Experts/Edit', $data);
        } catch (\Exception $e) {
            Log::error('Failed to load expert for edit: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard.experts.index')
                ->withErrors(['error' => 'Expert not found.']);
        }
    }

    /**
     * Update expert data
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
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'about' => 'nullable|string',
            'type' => 'nullable|array',
            'experiences' => 'nullable|array',
            'selected_skills' => 'nullable|array',
            'selected_skills.*' => 'exists:skills,id',
            'licenses' => 'nullable|array',
            'licenses.*.certification' => 'required_with:licenses|string|max:200',
            'licenses.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'gallerys' => 'nullable|array',
            'gallerys.*.file' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            $this->expertService->updateExpert($id, $request->all());

            return redirect()->route('dashboard.experts.show', $id)
                ->with('success', 'Expert updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update expert: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'Failed to update expert. Please try again.'])
                ->withInput();
        }
    }
    
}
