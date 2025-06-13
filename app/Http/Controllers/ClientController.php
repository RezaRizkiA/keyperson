<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expert;
use App\Models\Expertise;
use App\Models\ExpertiseCategory;

class ClientController extends Controller
{
    public function home_client($slug_page)
    {
        $client = Client::where('slug_page', $slug_page)->firstOrFail();

        $expertiseIds = is_array($client->expertise_id)
        ? $client->expertise_id
        : explode(',', $client->expertise_id);

        $expertises = Expertise::whereIn('id', $expertiseIds)
            ->with('category')
            ->get();

        $categoryIds = $expertises
            ->pluck('category_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $categories = ExpertiseCategory::whereIn('id', $categoryIds)
            ->with('expertises') // supaya bisa akses $category->expertises
            ->withCount('expertises')
            ->get();

        // Ambil semua expert yang memiliki minimal satu expertise_id milik client
        $allExperts = Expert::where(function ($query) use ($expertiseIds) {
            foreach ($expertiseIds as $id) {
                $query->orWhereJsonContains('expertise_id', (string) $id);
            }
        })->get();

        foreach ($categories as $category) {
            $category->selected_expertise_count = $expertises
                ->filter(fn($expertise) => $expertise->category_id == $category->id)
                ->count();

            // Ambil ID expertise client di kategori ini
            $clientExpertiseIdsInCategory = $expertises
                ->filter(fn($expertise) => $expertise->category_id == $category->id)
                ->pluck('id')
                ->all();

            // Ambil expert unik yang punya minimal satu expertise id di kategori ini
            $uniqueExpertIds = $allExperts
                ->filter(function ($expert) use ($clientExpertiseIdsInCategory) {
                    return count(array_intersect($clientExpertiseIdsInCategory, $expert->expertise_id)) > 0;
                })
                ->pluck('id')
                ->unique();

            $category->total_expert_in_category = $uniqueExpertIds->count();
        }

        return view('clients.home_client', compact('client', 'categories'));
    }

    public function list_conselor($slug_page, $slug)
    {
        // 1. Ambil data client berdasarkan slug_page
        $client = Client::where('slug_page', $slug_page)->firstOrFail();

        // 2. Ambil daftar expertise_id milik client
        $expertiseIds = is_array($client->expertise_id)
        ? $client->expertise_id
        : explode(',', $client->expertise_id);

        // 3. Ambil kategori berdasarkan slug
        $category = ExpertiseCategory::where('slug', $slug)->firstOrFail();

        // 4. Ambil semua expertise dalam kategori ini yang termasuk dalam expertise_id milik client
        $matchedExpertiseIds = $category->expertises()
            ->whereIn('id', $expertiseIds)
            ->pluck('id')
            ->toArray();
        // 5. Ambil semua expert yang memiliki expertise_id (JSON) mengandung ID-ID tersebut
        $experts = Expert::with('user')->where(function ($query) use ($matchedExpertiseIds) {
            foreach ($matchedExpertiseIds as $id) {
                $query->orWhereJsonContains('expertise_id', (string) $id);
            }
        })->get();
        // dd($experts);
        return view('clients.list_expert', compact('client', 'category', 'experts'));
    }

    public function expert_detail($expert_id)
    {
        $expert = Expert::with('user')->findOrFail($expert_id);
        $back = request('back'); // Tangkap query string back
        return view('clients.detail_expert', compact('expert', 'back'));
    }
}
