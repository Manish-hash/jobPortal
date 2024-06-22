<div class="admin-sidebar">
    <div class="card account-nav border-0 shadow mb-4 mb-lg-0 bg-dark text-white">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                <li id="dashboard-link" class="list-group-item d-flex justify-content-between p-3 bg-dark text-white">
                    <a href="{{ route('admin.dashboard') }}" class="text-white">Dashboard</a>
                </li>
                <li id="users-link" class="list-group-item d-flex justify-content-between align-items-center p-3 bg-dark text-white">
                    <a href="{{ route('admin.users') }}" class="text-white">Users</a>
                </li>
                <li id="jobs-link" class="list-group-item d-flex justify-content-between align-items-center p-3 bg-dark text-white">
                    <a href="{{ route('admin.jobs') }}" class="text-white">Jobs</a>
                </li>
                <li id="job-applications-link" class="list-group-item d-flex justify-content-between align-items-center p-3 bg-dark text-white">
                    <a href="{{ route('admin.jobApplications') }}" class="text-white">Jobs Applications</a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center p-3 bg-dark text-white">
                    <form id="logout-form" action="{{ route('account.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="{{ route('account.logout') }}" class="text-white"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

