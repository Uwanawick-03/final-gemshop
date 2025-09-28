<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%");
            });
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by material
        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }
        
        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
                case 'in_stock':
                    $query->where('current_stock', '>', 0)
                          ->whereRaw('current_stock > minimum_stock');
                    break;
            }
        }
        
        $items = $query->paginate(20);
        
        // Get filter options
        $categories = Item::distinct()->pluck('category')->filter();
        $materials = Item::distinct()->pluck('material')->filter();
        
        return view('items.index', compact('items', 'categories', 'materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        // Generate item code
        $itemCode = 'ITM-' . strtoupper(Str::random(6));
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $itemCode . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('items', $imageName, 'public');
        }
        
        $item = Item::create([
            'item_code' => $itemCode,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'material' => $request->material,
            'gemstone' => $request->gemstone,
            'weight' => $request->weight,
            'size' => $request->size,
            'purity' => $request->purity,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'wholesale_price' => $request->wholesale_price,
            'current_stock' => $request->current_stock,
            'minimum_stock' => $request->minimum_stock,
            'maximum_stock' => $request->maximum_stock,
            'unit' => $request->unit ?? 'piece',
            'barcode' => $request->barcode,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
            'is_taxable' => $request->has('is_taxable'),
            'tax_rate' => $request->tax_rate ?? 0,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('items.index')
            ->with('success', 'Item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        // Handle image upload
        $imagePath = $item->image; // Keep existing image by default
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            
            // Upload new image
            $image = $request->file('image');
            $imageName = $item->item_code . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('items', $imageName, 'public');
        }
        
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'material' => $request->material,
            'gemstone' => $request->gemstone,
            'weight' => $request->weight,
            'size' => $request->size,
            'purity' => $request->purity,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'wholesale_price' => $request->wholesale_price,
            'current_stock' => $request->current_stock,
            'minimum_stock' => $request->minimum_stock,
            'maximum_stock' => $request->maximum_stock,
            'unit' => $request->unit ?? 'piece',
            'barcode' => $request->barcode,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
            'is_taxable' => $request->has('is_taxable'),
            'tax_rate' => $request->tax_rate ?? 0,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        
        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully!');
    }
}
