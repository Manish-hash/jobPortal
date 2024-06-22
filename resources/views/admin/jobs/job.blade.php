@extends('layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4 bg-white shadow-sm">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Jobs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div>
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
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>

            <div class="col-lg-9">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body dashboard text-center">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <h3 class="fs-4 mb-0">Jobs</h3>
                                <!-- Add button or action here if needed -->
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Created By</th>
                                            <th scope="col">Applications</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if($jobs->isNotEmpty())
                                        @forelse($jobs as $job)
                                        <tr>
                                            <td>{{ $job->id }}</td>
                                            <td>{{ $job->title }}</td>
                                            <td>{{ $job->user->name }}</td>
                                            <td>{{ $job->applicationCount->count() }}</td>
                                            <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</td>
                                           @if($job->status == 1)
                                            <td class="text-success">Active</td>
                                            @else
                                            <td class="text-danger">Inactive</td>
                                            @endif
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteJob({{ $job->id }})">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No jobs found.</td>
                                        </tr>
                                        @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{ $jobs->links() }}
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
    function deleteJob(id) {
        if (confirm('Are you sure you want to delete this Job ?')) {
            $.ajax({
                url: '{{ route('admin.jobs.destroy') }}',
                type: 'DELETE', // Corrected from 'delete' to 'DELETE'
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    } else {
                        alert('Failed to delete job.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error deleting job. Please try again.');
                }
            });
        }
    }
</script>



@endsection
