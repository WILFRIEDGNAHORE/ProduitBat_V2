@extends('admin.layout.layout')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Categories Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Categories
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-header">
                                <h3 class="card-title">Categories</h3>
                                <a style="max-width: 150px; float:right; display: inline-block;"
                                    href="{{ url('admin/add-edit-category') }}" class="btn btn-primary">
                                    Add Category
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                <strong>Success: {{ Session::get('success_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-
                                        label="Close"></button>
                            </div>
                            @endif
                            <table id="categories" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Parent Category</th>
                                        <th>URL</th>
                                        <th>Created On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->parentcategory->name ?? '' }}</td>
                                        <td>{{ $category->url }}</td>
                                        <td>{{ $category->created_at->format('F j, Y, g:i a') }}</td>
                                        <td>
                                            <!-- Actions (Enable/Disable, Edit, Delete) will be added here -->
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
