<?php

namespace App\Http\Controllers;

use App\Models\Expertise;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpertiseController extends Controller
{
    public function store_expertise(Request $request){
        $expertise = Expertise::create($request->all());
        $expertise->slug = Str::slug($request->name);

        if($request->has('parent_id')){
            $expertise_parent = Expertise::with('childrens')->findOrFail($request->parent_id);
            $expertise->level = $expertise_parent->level + 1;
            // Ambil semua order anak, urutkan, dan cari celah
            $usedOrders = $expertise_parent->childrens->pluck('order')->filter()->unique()->sort()->values()->all();
            $order = collect(range(1, count($usedOrders) + 1))->diff($usedOrders)->first();
            $expertise->order = $order ?: count($usedOrders) + 1;
        }else{
            $expertise->level = 1;
            $usedOrders = Expertise::whereNull('parent_id')->pluck('order')->filter()->unique()->sort()->values()->all();
            $order = collect(range(1, count($usedOrders) + 1))->diff($usedOrders)->first();
            $expertise->order = $order ?: count($usedOrders) + 1;
        }

        // Menyimpan gambar baru ke S3
        if ($request->hasFIle('file_ilustration_img')) {
            $image    = $request->file('file_ilustration_img');
            $filename = 'expertise/' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put($filename, file_get_contents($image), 'public');
            $expertise->ilustration_img = $filename;
        }

        $expertise->save();
        return back();
    }

    public function update_expertise(Request $request, $expertise_id)
    {
        $expertise = Expertise::findOrFail($expertise_id);
        $expertise->fill($request->all());
        $expertise->slug = Str::slug($request->name);

        // Jika parent_id berubah, atur level & order baru
        if ($request->parent_id != $expertise->parent_id) {
            if ($request->parent_id) {
                $expertise_parent = Expertise::with('childrens')->findOrFail($request->parent_id);
                $expertise->level = $expertise_parent->level + 1;
                $usedOrders = $expertise_parent->childrens->pluck('order')->filter()->unique()->sort()->values()->all();
                $order = collect(range(1, count($usedOrders) + 1))->diff($usedOrders)->first();
                $expertise->order = $order ?: count($usedOrders) + 1;
            } else {
                $expertise->level = 1;
                $usedOrders = Expertise::whereNull('parent_id')->pluck('order')->filter()->unique()->sort()->values()->all();
                $order = collect(range(1, count($usedOrders) + 1))->diff($usedOrders)->first();
                $expertise->order = $order ?: count($usedOrders) + 1;
            }
        }

        // Update ilustration image jika ada
        if ($request->hasFile('file_ilustration_img')) {
            Storage::disk('s3')->delete($expertise->ilustration_img);
            $image = $request->file('file_ilustration_img');
            $filename = 'expertise/' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put($filename, file_get_contents($image), 'public');
            $expertise->ilustration_img = $filename;
        }

        $expertise->save();
        return back();
    }

    public function destroy_expertise($expertise_id)
    {
        $expertise = Expertise::with('childrensRecursive')->findOrFail($expertise_id);
        // Ambil semua descendant
        $flattened = collect([$expertise])->merge($expertise->flattenAllDescendants());

        // Hapus semua gambar ilustrasi
        foreach ($flattened as $item) {
            if (!empty($item->ilustration_img)) {
                Storage::disk('s3')->delete($item->ilustration_img);
            }
        }

        // Hapus root expertise, yang akan otomatis menghapus semua children (cascade)
        $expertise->delete();
        return back()->with('success', 'Skill dan semua sub-skill berhasil dihapus.');
    }
}
