@extends('admin.layout.layout')
@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Admin Management</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Details</li>
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
                            <div class="card-title">Update Details</div>
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
                        <form method="post" action="{{ route('admin.update-details.request') }}">
    @csrf
    <!--begin::Body-->
    <div class="card-body">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email"
                value="{{ Auth::guard('admin')->user()->email }}" readonly
                style="background-color: #ccc;">
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ Auth::guard('admin')->user()->name }}">
        </div>

        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input type="text" class="form-control" id="mobile" name="mobile"
                value="{{ Auth::guard('admin')->user()->mobile }}">
        </div>
    </div>
    <!--end::Body-->

    <!--begin::Footer-->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    <!--end::Footer-->
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
