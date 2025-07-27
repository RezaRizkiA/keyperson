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

        $expertises = Expertise::whereIn('id', $expertiseIds)->with('parent')
            ->get();

        return view('clients.home_client', compact('client', 'expertises'));
    }

    public function list_conselor($slug_page, $slug)
    {
        // 1. Ambil data client berdasarkan slug_page
        $client = Client::where('slug_page', $slug_page)->firstOrFail();

        // 3. Ambil kategori berdasarkan slug
        $expertise = Expertise::where('slug', $slug)->firstOrFail();

        // dd($experts);
        return view('clients.list_expert', compact('client', 'expertise'));
    }

    public function expert_detail($expert_id) {
        $expert = Expert::with('user')->findOrFail($expert_id);
        $back = request('back'); // Tangkap query string back
        
        return view('clients.detail_expert', compact('expert', 'back'));
    }
}
