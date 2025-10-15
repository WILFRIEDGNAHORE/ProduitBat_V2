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
                <div class="col-md-8">
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
                                    <label class="form-label" for="product_color">Product Color *</label>
                                    <input type="text" name="product_color" class="form-control"
                                        value="{{ old('product_color', $product->product_color ?? '') }}"
                                        placeholder="Enter Product Color">
                                </div>

                                <?php $familyColors = \App\Models\Color::colors(); ?>
                                <div class="mb-3">
                                    <label class="form-label" for="family_color">Family Color *</label>
                                    <select name="family_color" class="form-control">
                                        <option value="">Please Select</option>
                                        @foreach($familyColors as $color)
                                        <option value="{{ $color->name }}"
                                            @if(isset($product['family_color']) && $product['family_color']==$color->name) selected @endif>
                                            {{ $color->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="group_code">Group Code *</label>
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
                                    <label class="form-label mb-1">Product Attributes</label>

                                    <!-- Header row -->
                                    <div class="d-none d-md-flex fw-semibold bg-light border rounded px-2 py-1 mb-2">
                                        <div class="flex-fill col-2">Size</div>
                                        <div class="flex-fill col-2 ms-4">SKU</div>
                                        <div class="flex-fill col-2 ms-4">Price</div>
                                        <div class="flex-fill col-2 ms-4">Stock</div>
                                        <div class="flex-fill col-2 ms-4">Sort</div>
                                        <div style="width:60px"></div>
                                    </div>

                                    <!-- Dynamic attribute rows -->
                                    <div class="field_wrapper">
                                        <!-- First (default) row -->
                                        <div class="d-flex align-items-center gap-2 mb-2 attribute-row">
                                            <input type="text" name="size[]" class="form-control flex-fill col-2" placeholder="Size">
                                            <input type="text" name="sku[]" class="form-control flex-fill col-2" placeholder="SKU">
                                            <input type="number" step="0.01" name="price[]" class="form-control flex-fill col-2" placeholder="Price">
                                            <input type="number" name="stock[]" class="form-control flex-fill col-2" placeholder="Stock">
                                            <input type="number" name="sort[]" class="form-control flex-fill col-2" placeholder="Sort">

                                            <!-- Add button -->
                                            <a href="javascript:void(0);" class="btn btn-sm btn-success add_button" title="Add row">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                @if(isset($product['attributes']) && count($product['attributes']) > 0)
                                <div class="mb-3">
                                    <label class="form-label mb-1">Existing Product Attributes</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle mb-0">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th style="width: 15%;">Size</th>
                                                    <th style="width: 20%;">SKU</th>
                                                    <th style="width: 15%;">Price</th>
                                                    <th style="width: 15%;">Stock</th>
                                                    <th style="width: 15%;">Sort</th>
                                                    <th style="width: 15%;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($product['attributes'] as $attribute)
                                                <tr class="text-center">
                                                    {{-- Hidden field for attribute ID --}}
                                                    <input type="hidden" name="attrId[]" value="{{ $attribute['id'] }}">

                                                    <td>{{ $attribute['size'] }}</td>
                                                    <td>{{ $attribute['sku'] }}</td>

                                                    <td>
                                                        <input type="number"
                                                            name="update_price[]"
                                                            value="{{ $attribute['price'] }}"
                                                            class="form-control text-center"
                                                            required>
                                                    </td>

                                                    <td>
                                                        <input type="number"
                                                            name="update_stock[]"
                                                            value="{{ $attribute['stock'] }}"
                                                            class="form-control text-center"
                                                            required>
                                                    </td>

                                                    <td>
                                                        <input type="number"
                                                            name="update_sort[]"
                                                            value="{{ $attribute['sort'] }}"
                                                            class="form-control text-center"
                                                            required>
                                                    </td>

                                                    <td>
                                                        {{-- Toggle Status --}}
                                                        @if($attribute['status'] == 1)
                                                        <a class="updateAttributeStatus text-primary me-2"
                                                            id="attribute-{{ $attribute['id'] }}"
                                                            attribute_id="{{ $attribute['id'] }}"
                                                            href="javascript:void(0)">
                                                            <i class="fas fa-toggle-on" status="Active"></i>
                                                        </a>
                                                        @else
                                                        <a class="updateAttributeStatus text-secondary me-2"
                                                            id="attribute-{{ $attribute['id'] }}"
                                                            attribute_id="{{ $attribute['id'] }}"
                                                            href="javascript:void(0)">
                                                            <i class="fas fa-toggle-off" status="Inactive"></i>
                                                        </a>
                                                        @endif

                                                        {{-- Delete Attribute --}}
                                                        <a title="Delete Attribute"
                                                            href="javascript:void(0)"
                                                            class="confirmDelete text-danger"
                                                            record="attribute"
                                                            recordid="{{ $attribute['id'] }}">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif



                                <!-- Product Main Image Upload Field -->
                                <div class="mb-3">
                                    <label class="form-label" for="main_image_dropzone">Product Main Image (Max 500 KB)</label>
                                    <div class="dropzone" id="mainimageDropzone"></div>

                                    <!-- Hidden input to send uploaded image -->
                                    <input type="hidden" name="main_image" id="main_image_hidden">

                                    @if(!empty($product['main_image']))
                                    <a target="_blank" href="{{ url('front/images/products/' . $product['main_image']) }}">
                                        <img style="width:50px; margin:10px;" src="{{ asset('front/images/products/' . $product['main_image']) }}" alt="Product Image">
                                    </a>
                                    <a style="color:#3f6ed3;" class="confirmDelete" title="Delete Product Image"
                                        href="javascript:void(0)" data-module="product-main-image" data-id="{{ $product['id'] }}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    @endif


                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="product_images_dropzone">
                                        Alternate Product Images (Multiple Uploads Allowed, Max 500 KB each)
                                    </label>
                                    <div class="dropzone" id="productimagesDropzone"></div>

                                    @if(isset($product->product_images) && $product->product_images->count() > 0)
                                    @foreach($product->product_images as $img)
                                    <div style="display:inline-block; position:relative; margin:5px;">
                                        <a target="_blank" href="{{ url('front/images/products/' . $img->image) }}">
                                            <img src="{{ asset('front/images/products/' . $img->image) }}" style="width:50px;">
                                        </a>
                                        <a href="javascript:void(0)" class="confirmDelete"
                                            data-module="product-image"
                                            data-id="{{ $img->id }}"
                                            data-image="{{ $img->image }}">
                                            <i class="fas fa-trash" style="position:absolute; top:0; right:0; color:red;"></i>
                                        </a>
                                    </div>
                                    @endforeach
                                    @endif

                                    <!-- Hidden input to collect alternate images -->
                                    <input type="hidden" name="product_images" id="product_images_hidden">
                                </div>


                                <!-- Product Video Upload Field -->
                                <div class="mb-3">
                                    <label class="form-label" for="product_video_dropzone">Product Video (Max 2 MB)</label>
                                    <div class="dropzone" id="productVideoDropzone"></div>

                                    <!-- Hidden input to send uploaded video -->
                                    <input type="hidden" name="product_video" id="product_video_hidden">

                                    @if(!empty($product['product_video']))
                                    <a target="_blank" href="{{ url('front/videos/products/' . $product['product_video']) }}">View Video</a> |
                                    <a href="javascript:void(0)" class="confirmDelete" data-module="product-video" data-id="{{ $product['id'] }}">
                                        Delete Video
                                    </a>
                                    @endif


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
