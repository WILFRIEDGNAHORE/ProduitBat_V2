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
                        <li class="breadcrumb-item active" aria-current="page">Update Password</li>
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
                            <div class="card-title">Update Password</div>
                        </div>

                        <!--begin::Form-->
                        <form method="post" action="{{ route('admin.update-password') }}">
                            @csrf
                            <div class="card-body">
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="email"
                                        aria-describedby="emailHelp"
                                        value="{{ Auth::guard('admin')->user()->email }}" 
                                        readonly style="background-color: #ccc"
                                    />
                                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                                </div>

                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label for="current_pwd" class="form-label">Current Password</label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        id="current_pwd"
                                        name="current_pwd"
                                    />
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label for="new_pwd" class="form-label">New Password</label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        id="new_pwd" 
                                        name="new_pwd"
                                    />
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="confirm_pwd" class="form-label">Confirm Password</label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        id="confirm_pwd" 
                                        name="confirm_pwd"
                                    />
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
