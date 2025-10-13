@extends('admin.layout.layout')
@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Product Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-6">
                    <!--begin::Card-->
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">{{ $title }}</div>
                        </div>
                        @if(Session::has('error_message'))
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            <strong>Error: </strong> {{ Session::get('error_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                        @endif

                        @if(Session::has('success_message'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <strong>Success: </strong> {{ Session::get('success_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                        @endif

                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            <strong>Error!</strong> {!! $error !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                        @endforeach


                        <!--begin::Form-->
                        <form name="productForm" id="productForm"
                            action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
                            method="post">
                            @csrf
                            @if(isset($product)) @method('PUT') @endif

                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="category_id">Category Level (Select Category) *</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($getCategories as $cat)
                                        <option value="{{ $cat['id'] }}"
                                            @if(old('category_id', $product->category_id ?? "") == $cat['id']) selected @endif>
                                            {{ $cat['name'] }}
                                        </option>

                                        @if(!empty($cat['subcategories']))
                                        @foreach($cat['subcategories'] as $subcat)
                                        <option value="{{ $subcat['id'] }}"
                                            @if(old('category_id', $product->category_id ?? "") == $subcat['id']) selected @endif>
                                            &nbsp;&nbsp;&raquo;&raquo; {{ $subcat['name'] }}
                                        </option>

                                        @if(!empty($subcat['subcategories']))
                                        @foreach($subcat['subcategories'] as $subsubcat)
                                        <option value="{{ $subsubcat['id'] }}"
                                            @if(old('category_id', $product->category_id ?? "") == $subsubcat['id']) selected @endif>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; {{ $subsubcat['name'] }}
                                        </option>
                                        @endforeach
                                        @endif
                                        @endforeach
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product_name">Product Name *</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                        value="{{ old('product_name', $product->product_name ?? '') }}"
                                        placeholder="Enter Product Name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product_code">Product Code *</label>
                                    <input type="text" class="form-control" id="product_code" name="product_code"
                                        value="{{ old('product_code', $product->product_code ?? '') }}"
                                        placeholder="Enter Product Code">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product_color">Product Color</label>
                                    <input type="text" name="product_color" class="form-control"
                                        value="{{ old('product_color', $product->product_color ?? '') }}"
                                        placeholder="Enter Product Color">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="family_color">Family Color</label>
                                    <input type="text" name="family_color" class="form-control"
                                        value="{{ old('family_color', $product->family_color ?? '') }}"
                                        placeholder="Enter Family Color">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="group_code">Group Code</label>
                                    <input type="text" name="group_code" class="form-control"
                                        value="{{ old('group_code', $product->group_code ?? '') }}"
                                        placeholder="Enter Group Code">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product_price">Product Price *</label>
                                    <input type="text" class="form-control" id="product_price" name="product_price"
                                        value="{{ old('product_price', $product->product_price ?? '') }}"
                                        placeholder="Enter Product Price">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product_discount">Product Discount (%)</label>
                                    <input type="number" step="0.01" name="product_discount" class="form-control"
                                        value="{{ old('product_discount', $product->product_discount ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product_gst">Product GST (%)</label>
                                    <input type="number" step="0.01" name="product_gst" class="form-control"
                                        value="{{ old('product_gst', $product->product_gst ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product_weight">Product Weight (grams)</label>
                                    <input type="number" step="0.01" name="product_weight" class="form-control"
                                        value="{{ old('product_weight', $product->product_weight ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="wash_care">Wash Care</label>
                                    <textarea name="wash_care" class="form-control" placeholder="Enter Wash Care">{{ old('wash_care', $product->wash_care ?? '') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Product Description</label>
                                    <textarea class="form-control" id="description" name="description"
                                        rows="3" placeholder="Enter Product Description">{{ old('description', $product->description ?? '') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="search_keywords">Search Keywords</label>
                                    <textarea name="search_keywords" class="form-control" placeholder="Enter Search Keywords">{{ old('search_keywords', $product->search_keywords ?? '') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="meta_title">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control"
                                        value="{{ old('meta_title', $product->meta_title ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="meta_description">Meta Description</label>
                                    <input type="text" name="meta_description" class="form-control"
                                        value="{{ old('meta_description', $product->meta_description ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="meta_keywords">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control"
                                        value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="is_featured">Is Featured ?</label>
                                    <select name="is_featured" class="form-select">
                                        <option value="No" {{ (old('is_featured', $product->is_featured ?? '') == 'No') ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ (old('is_featured', $product->is_featured ?? '') == 'Yes') ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>



                        <!--end::Form-->
                    </div>
                    <!--end::Card-->
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content-->
</main>
@endsection
