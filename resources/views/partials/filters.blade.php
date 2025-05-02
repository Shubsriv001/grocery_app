<!-- Filters Content -->
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-4">Filters</h5>
        <form action="{{ route('home') }}" method="GET" id="filterForm">
            <!-- Categories -->
            <div class="mb-4">
                <h6 class="mb-3">Categories</h6>
                <div class="form-check">
                    <input class="form-check-input filter-input" type="radio" 
                        name="category" value="all" 
                        id="catAll"
                        {{ (!request('category') || request('category') == 'all') ? 'checked' : '' }}>
                    <label class="form-check-label" for="catAll">
                        All Categories
                    </label>
                </div>
                @foreach($categories as $cat)
                    <div class="form-check">
                        <input class="form-check-input filter-input" type="radio" 
                            name="category" value="{{ $cat }}" 
                            id="cat{{ $loop->index }}"
                            {{ request('category') == $cat ? 'checked' : '' }}>
                        <label class="form-check-label" for="cat{{ $loop->index }}">
                            {{ ucfirst($cat) }}
                        </label>
                    </div>
                @endforeach
            </div>

            <!-- Price Range -->
            <div class="mb-4">
                <h6 class="mb-3">Price Range</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" class="form-control filter-input" 
                            name="min_price" placeholder="{{ $priceRange['min'] }}" 
                            value="{{ request('min_price', $priceRange['min']) }}">
                    </div>
                    <div class="col-6">
                        <input type="number" class="form-control filter-input" 
                            name="max_price" placeholder="{{ $priceRange['max'] }}"
                            value="{{ request('max_price', $priceRange['max']) }}">
                    </div>
                </div>
            </div>

            <!-- Sort By -->
            <div class="mb-4">
                <h6 class="mb-3">Sort By</h6>
                <select class="form-select filter-input" name="sort" onchange="this.form.submit()">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                    <option value="category" {{ request('sort') == 'category' ? 'selected' : '' }}>Category</option>
                </select>
            </div>

            <!-- Sort Direction -->
            <div class="mb-4">
                <h6 class="mb-3">Order</h6>
                <select class="form-select filter-input" name="direction" onchange="this.form.submit()">
                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>

            <!-- Clear Filters -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Clear Filters</a>
            </div>
        </form>
    </div>
</div>
