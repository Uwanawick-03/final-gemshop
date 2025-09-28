<?php

namespace App\Http\Controllers;

use App\Models\TourGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourGuideController extends Controller
{
    public function index(Request $request)
    {
        $query = TourGuide::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('guide_code', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('language')) {
            $query->whereJsonContains('languages', $request->language);
        }

        if ($request->filled('area')) {
            $query->whereJsonContains('service_areas', $request->area);
        }

        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }

        $tourGuides = $query->orderBy('created_at', 'desc')->paginate(20);

        $languages = TourGuide::select('languages')
            ->whereNotNull('languages')
            ->pluck('languages')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $areas = TourGuide::select('service_areas')
            ->whereNotNull('service_areas')
            ->pluck('service_areas')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('tour-guides.index', compact('tourGuides', 'languages', 'areas'));
    }

    public function create()
    {
        return view('tour-guides.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:tour_guides,email',
            'phone' => 'required|string|max:20',
            'joined_date' => 'nullable|date',
            'license_expiry' => 'nullable|date',
            'daily_rate' => 'nullable|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated,on_leave',
        ]);

        $code = 'TG-' . strtoupper(Str::random(6));

        TourGuide::create([
            'guide_code' => $code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'national_id' => $request->national_id,
            'joined_date' => $request->joined_date,
            'languages' => $request->languages ? array_map('trim', explode(',', $request->languages)) : null,
            'service_areas' => $request->service_areas ? array_map('trim', explode(',', $request->service_areas)) : null,
            'license_number' => $request->license_number,
            'license_expiry' => $request->license_expiry,
            'daily_rate' => $request->daily_rate,
            'employment_status' => $request->employment_status,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('tour-guides.index')->with('success', 'Tour guide created successfully!');
    }

    public function show(TourGuide $tourGuide)
    {
        // Don't load relationships that don't exist yet to avoid database errors
        // $tourGuide->load(['tours']);
        
        return view('tour-guides.show', compact('tourGuide'));
    }

    public function edit(TourGuide $tourGuide)
    {
        return view('tour-guides.edit', compact('tourGuide'));
    }

    public function update(Request $request, TourGuide $tourGuide)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:tour_guides,email,' . $tourGuide->id,
            'phone' => 'required|string|max:20',
            'joined_date' => 'nullable|date',
            'license_expiry' => 'nullable|date',
            'daily_rate' => 'nullable|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated,on_leave',
        ]);

        $tourGuide->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'national_id' => $request->national_id,
            'joined_date' => $request->joined_date,
            'languages' => $request->languages ? array_map('trim', explode(',', $request->languages)) : null,
            'service_areas' => $request->service_areas ? array_map('trim', explode(',', $request->service_areas)) : null,
            'license_number' => $request->license_number,
            'license_expiry' => $request->license_expiry,
            'daily_rate' => $request->daily_rate,
            'employment_status' => $request->employment_status,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('tour-guides.show', $tourGuide)->with('success', 'Tour guide updated successfully!');
    }

    public function destroy(TourGuide $tourGuide)
    {
        $tourGuide->delete();
        return redirect()->route('tour-guides.index')->with('success', 'Tour guide deleted successfully!');
    }
}
