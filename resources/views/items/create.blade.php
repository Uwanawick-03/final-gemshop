@extends('layouts.app')

@section('title', 'Add New Item')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>Add New Item</h2>
    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Items
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Item Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Ring" {{ old('category') == 'Ring' ? 'selected' : '' }}>Ring</option>
                                <option value="Necklace" {{ old('category') == 'Necklace' ? 'selected' : '' }}>Necklace</option>
                                <option value="Earring" {{ old('category') == 'Earring' ? 'selected' : '' }}>Earring</option>
                                <option value="Bracelet" {{ old('category') == 'Bracelet' ? 'selected' : '' }}>Bracelet</option>
                                <option value="Pendant" {{ old('category') == 'Pendant' ? 'selected' : '' }}>Pendant</option>
                                <option value="Chain" {{ old('category') == 'Chain' ? 'selected' : '' }}>Chain</option>
                                <option value="Watch" {{ old('category') == 'Watch' ? 'selected' : '' }}>Watch</option>
                                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subcategory" class="form-label">Subcategory</label>
                            <input type="text" class="form-control @error('subcategory') is-invalid @enderror" 
                                   id="subcategory" name="subcategory" value="{{ old('subcategory') }}">
                            @error('subcategory')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="material" class="form-label">Material <span class="text-danger">*</span></label>
                            <select class="form-select @error('material') is-invalid @enderror" 
                                    id="material" name="material" required>
                                <option value="">Select Material</option>
                                <option value="Gold" {{ old('material') == 'Gold' ? 'selected' : '' }}>Gold</option>
                                <option value="Silver" {{ old('material') == 'Silver' ? 'selected' : '' }}>Silver</option>
                                <option value="Platinum" {{ old('material') == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                                <option value="Diamond" {{ old('material') == 'Diamond' ? 'selected' : '' }}>Diamond</option>
                                <option value="Pearl" {{ old('material') == 'Pearl' ? 'selected' : '' }}>Pearl</option>
                                <option value="Gemstone" {{ old('material') == 'Gemstone' ? 'selected' : '' }}>Gemstone</option>
                                <option value="Other" {{ old('material') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('material')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gemstone" class="form-label">Gemstone</label>
                            <input type="text" class="form-control @error('gemstone') is-invalid @enderror" 
                                   id="gemstone" name="gemstone" value="{{ old('gemstone') }}" 
                                   placeholder="e.g., Diamond, Ruby, Sapphire">
                            @error('gemstone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="weight" class="form-label">Weight (grams)</label>
                            <input type="number" step="0.001" class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" name="weight" value="{{ old('weight') }}">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="size" class="form-label">Size</label>
                            <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                   id="size" name="size" value="{{ old('size') }}" 
                                   placeholder="e.g., Ring size 7, 18 inch chain">
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="purity" class="form-label">Purity (Karat)</label>
                            <select class="form-select @error('purity') is-invalid @enderror" 
                                    id="purity" name="purity">
                                <option value="">Select Purity</option>
                                <option value="9" {{ old('purity') == '9' ? 'selected' : '' }}>9K</option>
                                <option value="14" {{ old('purity') == '14' ? 'selected' : '' }}>14K</option>
                                <option value="18" {{ old('purity') == '18' ? 'selected' : '' }}>18K</option>
                                <option value="22" {{ old('purity') == '22' ? 'selected' : '' }}>22K</option>
                                <option value="24" {{ old('purity') == '24' ? 'selected' : '' }}>24K</option>
                            </select>
                            @error('purity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Image Upload -->
                    <hr class="my-4">
                    <h6 class="text-primary mb-3">Item Image</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Item Photo</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Supported formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB
                                </small>
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pricing Information -->
                    <hr class="my-4">
                    <h6 class="text-primary mb-3">Pricing Information</h6>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cost_price" class="form-label">Cost Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ getDisplayCurrency()->symbol ?? 'Rs' }}</span>
                                <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" 
                                       id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required>
                            </div>
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="selling_price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ getDisplayCurrency()->symbol ?? 'Rs' }}</span>
                                <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" 
                                       id="selling_price" name="selling_price" value="{{ old('selling_price') }}" required>
                            </div>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="wholesale_price" class="form-label">Wholesale Price</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ getDisplayCurrency()->symbol ?? 'Rs' }}</span>
                                <input type="number" step="0.01" class="form-control @error('wholesale_price') is-invalid @enderror" 
                                       id="wholesale_price" name="wholesale_price" value="{{ old('wholesale_price') }}">
                            </div>
                            @error('wholesale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Stock Information -->
                    <hr class="my-4">
                    <h6 class="text-primary mb-3">Stock Information</h6>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="current_stock" class="form-label">Current Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('current_stock') is-invalid @enderror" 
                                   id="current_stock" name="current_stock" value="{{ old('current_stock', 0) }}" required>
                            @error('current_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="minimum_stock" class="form-label">Minimum Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror" 
                                   id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" required>
                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <select class="form-select @error('unit') is-invalid @enderror" 
                                    id="unit" name="unit">
                                <option value="piece" {{ old('unit', 'piece') == 'piece' ? 'selected' : '' }}>Piece</option>
                                <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                                <option value="carat" {{ old('unit') == 'carat' ? 'selected' : '' }}>Carat</option>
                                <option value="inch" {{ old('unit') == 'inch' ? 'selected' : '' }}>Inch</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                   id="barcode" name="barcode" value="{{ old('barcode') }}">
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                            <input type="number" step="0.01" class="form-control @error('tax_rate') is-invalid @enderror" 
                                   id="tax_rate" name="tax_rate" value="{{ old('tax_rate', 0) }}">
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <hr class="my-4">
                    <h6 class="text-primary mb-3">Additional Information</h6>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Status Options -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Item
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_taxable" name="is_taxable" 
                                       {{ old('is_taxable', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_taxable">
                                    Taxable Item
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Help Panel -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Help & Tips</h6>
            </div>
            <div class="card-body">
                <h6>Item Categories:</h6>
                <ul class="list-unstyled small">
                    <li>• <strong>Ring:</strong> Wedding rings, engagement rings, fashion rings</li>
                    <li>• <strong>Necklace:</strong> Chains, pendants, chokers</li>
                    <li>• <strong>Earring:</strong> Studs, hoops, drops</li>
                    <li>• <strong>Bracelet:</strong> Bangles, chains, cuffs</li>
                </ul>
                
                <h6 class="mt-3">Materials:</h6>
                <ul class="list-unstyled small">
                    <li>• <strong>Gold:</strong> 9K, 14K, 18K, 22K, 24K</li>
                    <li>• <strong>Silver:</strong> Sterling silver, fine silver</li>
                    <li>• <strong>Platinum:</strong> 950, 900 platinum</li>
                </ul>
                
                <h6 class="mt-3">Pricing Tips:</h6>
                <ul class="list-unstyled small">
                    <li>• Cost price should be lower than selling price</li>
                    <li>• Wholesale price is typically 20-30% below selling price</li>
                    <li>• Consider material costs and labor when pricing</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });
});
</script>
@endsection



