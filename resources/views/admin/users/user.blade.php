@extends('layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4 bg-white shadow-sm">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>

            @if(Session::has('success'))
            <div class="alert alert-success">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        @if(Session::has('error'))
        <div class="alert alert-danger">
            <p>{{ Session::get('error') }}</p>
        </div>
        @endif

            <div class="col-lg-9">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body dashboard text-center">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <h3 class="fs-4 mb-0">Users</h3>
                                <!-- Add button or action here if needed -->
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Designation</th>
                                            <th scope="col">Mobile</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->designation }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $user->id }})">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No users found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $users->links() }}
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
    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: '{{ route('admin.users.destroy') }}',
                type: 'DELETE', // Corrected from 'delete' to 'DELETE'
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    } else {
                        alert('Failed to delete user.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error deleting user. Please try again.');
                }
            });
        }
    }
</script>


{{-- <script type="text/javascript">
    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {

            $.ajax({
                url: '{{ route('admin.users.destroy') }}',
                type:'delete';
                data: { id: id},
                dataType: 'json',
                success: function(reaponse){
                    window.location.href ="{{ route('admin.users') }}"
                }
            })
         
        }
    }
</script> --}}
@endsection
