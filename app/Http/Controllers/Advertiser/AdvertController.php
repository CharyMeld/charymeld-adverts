<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\AdvertImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdvertController extends Controller
{
    public function index()
    {
        $status = request('status', 'all');

        $query = auth()->user()->adverts()
            ->with(['category', 'primaryImage'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $adverts = $query->paginate(10);

        $stats = [
            'total' => auth()->user()->adverts()->count(),
            'approved' => auth()->user()->adverts()->where('status', 'approved')->count(),
            'pending' => auth()->user()->adverts()->where('status', 'pending')->count(),
            'rejected' => auth()->user()->adverts()->where('status', 'rejected')->count(),
            'expired' => auth()->user()->adverts()->where('status', 'expired')->count(),
        ];

        return view('advertiser.adverts.index', compact('adverts', 'stats'));
    }

    public function create()
    {
        $categories = Category::active()->parents()->with('children')->get();
        return view('advertiser.adverts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $advert = Advert::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'pending',
            'plan' => 'regular',
            'is_active' => false,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('adverts', 'public');

                AdvertImage::create([
                    'advert_id' => $advert->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0, // First image is primary
                ]);
            }
        }

        return redirect()->route('advertiser.adverts.payment', $advert->id)
            ->with('success', 'Advert created successfully! Please complete payment to activate.');
    }

    public function edit(Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::active()->parents()->with('children')->get();
        return view('advertiser.adverts.edit', compact('advert', 'categories'));
    }

    public function update(Request $request, Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $advert->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('adverts', 'public');

                AdvertImage::create([
                    'advert_id' => $advert->id,
                    'image_path' => $path,
                    'is_primary' => $advert->images()->count() === 0,
                ]);
            }
        }

        return redirect()->route('advertiser.adverts.index')
            ->with('success', 'Advert updated successfully!');
    }

    public function destroy(Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete images from storage
        foreach ($advert->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $advert->delete();

        return redirect()->route('advertiser.adverts.index')
            ->with('success', 'Advert deleted successfully!');
    }

    public function deleteImage(AdvertImage $image)
    {
        // Check ownership
        if ($image->advert->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully!');
    }

    /**
     * Show advert preview (for advertiser to view their own adverts)
     */
    public function show(Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to view this advert.');
        }

        // Load relationships
        $advert->load(['category', 'images', 'user']);

        // Get similar adverts from same category
        $similarAdverts = Advert::where('category_id', $advert->category_id)
            ->where('id', '!=', $advert->id)
            ->active()
            ->approved()
            ->notExpired()
            ->with('primaryImage')
            ->limit(4)
            ->get();

        return view('advertiser.adverts.show', compact('advert', 'similarAdverts'));
    }

    public function showPayment(Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        $plans = [
            'regular' => ['price' => 1000, 'duration' => 30, 'features' => ['Standard listing', '30 days visibility']],
            'featured' => ['price' => 3000, 'duration' => 30, 'features' => ['Featured listing', 'Top search results', '30 days visibility']],
            'premium' => ['price' => 5000, 'duration' => 60, 'features' => ['Premium listing', 'Homepage featured', 'Top search results', '60 days visibility']],
        ];

        return view('advertiser.adverts.payment', compact('advert', 'plans'));
    }

    /**
     * Show campaign creation form
     */
    public function createCampaign()
    {
        $categories = Category::active()->parents()->with('children')->get();
        return view('advertiser.campaigns.create', compact('categories'));
    }

    /**
     * Store new campaign
     */
    public function storeCampaign(Request $request)
    {
        $request->validate([
            'ad_type' => 'required|in:classified,banner,text,video',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'pricing_model' => 'required|in:flat,cpc,cpm,cpa',
            'budget' => 'required|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'target_countries' => 'nullable|array',
            'target_devices' => 'nullable|array',
            'target_keywords' => 'nullable|string',
            'banner_image' => 'nullable|image|max:5120',
            'banner_url' => 'nullable|url',
            'banner_size' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'ad_type' => $request->ad_type,
            'title' => $request->title,
            'description' => $request->description,
            'pricing_model' => $request->pricing_model,
            'budget' => $request->budget,
            'cost_per_click' => $request->pricing_model === 'cpc' ? $request->cost_per_unit : null,
            'cost_per_impression' => $request->pricing_model === 'cpm' ? $request->cost_per_unit : null,
            'cost_per_action' => $request->pricing_model === 'cpa' ? $request->cost_per_unit : null,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'target_countries' => $request->target_countries,
            'target_devices' => $request->target_devices,
            'target_keywords' => $request->target_keywords ? explode(',', $request->target_keywords) : null,
            'status' => 'pending',
            'is_active' => false,
        ];

        // Handle banner ad fields
        if ($request->ad_type === 'banner' || $request->ad_type === 'text') {
            if ($request->hasFile('banner_image')) {
                $data['banner_image'] = $request->file('banner_image')->store('banners', 'public');
            }
            $data['banner_url'] = $request->banner_url;
            $data['banner_size'] = $request->banner_size;
        }

        // Handle classified ad fields
        if ($request->ad_type === 'classified') {
            $data['price'] = $request->price;
            $data['location'] = $request->location;
            $data['contact_name'] = $request->contact_name;
            $data['contact_phone'] = $request->contact_phone;
            $data['contact_email'] = $request->contact_email;
        }

        $advert = Advert::create($data);

        // Handle classified images
        if ($request->ad_type === 'classified' && $request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('adverts', 'public');
                AdvertImage::create([
                    'advert_id' => $advert->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('advertiser.adverts.payment', $advert->id)
            ->with('success', 'Campaign created successfully! Please complete payment to activate.');
    }
}
