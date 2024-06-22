@extends('layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4 bg-white shadow-sm">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body ">
                        <div class="card-body card-form">
                            <form action="{{ route('admin.users.update', $user->id ) }}" method="post" id="userUpdate" name="userUpdate">
                                @csrf
                                @method('PUT')
                                <div class="card-body p-4">
                                    <h3 class="fs-4 mb-1">User Edit</h3>
                                    <div class="mb-4">
                                        <label for="name" class="mb-2">Name*</label>
                                        <input type="text" name="name" id="name" placeholder="Enter Name" class="form-control" value="{{ $user->name }}">
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="email" class="mb-2">Email*</label>
                                        <input type="text" name="email" id="email" placeholder="Enter Email" class="form-control" value="{{ $user->email }}">
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="designation" class="mb-2">Designation*</label>
                                        <input type="text" name="designation" id="designation" placeholder="Designation" class="form-control" value="{{ $user->designation }}">
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="phone" class="mb-2">Phone*</label>
                                        <input type="text" name="phone" id="phone" placeholder="Mobile" class="form-control" value="{{ $user->phone }}">
                                        <p></p>
                                    </div>                        
                                </div>
                                <div class="card-footer p-4">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">

    $('#userUpdate').submit(function(e){
    e.preventDefault();

    $.ajax({
        url: '{{ route("account.update-profile") }}',
        type: 'POST',
        dataType: 'json',
        data: $("#userUpdate").serializeArray(),
        success: function(response){
            if(response.status == true){
                $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('')

                 $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('')

                window.location.href="{{ route('admin.users') }}";
            } else {
                var errors = response.errors;

                if(errors.name){
                    $("#name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.name)
                } else {
                    $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('')
                }

                if(errors.email){
                    $("#email").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.email)
                } else {
                    $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('')
                }

               
            }
        }
    });
});

</script>
@endsection
