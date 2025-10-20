@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.security.reports.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Reports Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Report Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                        <h1 class="text-2xl font-bold text-white">Security Report #{{ $report->id }}</h1>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Report Type</span>
                                <p class="mt-1 text-gray-900 font-medium">{{ str_replace('_', ' ', ucwords($report->report_type)) }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Submitted</span>
                                <p class="mt-1 text-gray-900">{{ $report->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Subject</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $report->subject }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $report->description }}</p>
                            </div>
                        </div>

                        @if($report->url)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">URL / Location</h3>
                                <a href="{{ $report->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all">
                                    {{ $report->url }} ↗
                                </a>
                            </div>
                        @endif

                        @if($report->reporter)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-blue-900 mb-2">Reporter Information</h3>
                                <div class="space-y-1 text-sm">
                                    <p><strong>Name:</strong> {{ $report->reporter->name }}</p>
                                    <p><strong>Email:</strong> {{ $report->reporter->email }}</p>
                                    <p><strong>IP Address:</strong> {{ $report->ip_address }}</p>
                                </div>
                            </div>
                        @endif

                        @if($report->reportedUser)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-yellow-900 mb-2">Reported User</h3>
                                <div class="space-y-1 text-sm">
                                    <p><strong>Name:</strong> {{ $report->reportedUser->name }}</p>
                                    <p><strong>Email:</strong> {{ $report->reportedUser->email }}</p>
                                    <p><strong>Role:</strong> {{ ucfirst($report->reportedUser->role) }}</p>
                                    <a href="{{ route('admin.users.show', $report->reportedUser->id) }}" class="text-blue-600 hover:text-blue-800 inline-block mt-2">
                                        View User Profile →
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Take Action on User -->
                @if($report->reportedUser && $report->status !== 'resolved')
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Take Action on User</h2>
                        <form action="{{ route('admin.security.reports.action', $report->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Action Type</label>
                                <select name="action" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="">Select action...</option>
                                    <option value="warn">Send Warning</option>
                                    <option value="suspend">Suspend Account</option>
                                    <option value="ban">Ban Account</option>
                                    <option value="reset_password">Force Password Reset</option>
                                </select>
                            </div>

                            <div id="duration-field" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Suspension Duration (days)</label>
                                <input type="number" name="duration" min="1" max="365" value="7" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                                <textarea name="reason" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Explain why this action is being taken..."></textarea>
                            </div>

                            <button type="submit" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                                Execute Action
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Update Report -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Update Report</h2>
                    <form action="{{ route('admin.security.reports.update', $report->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="investigating" {{ $report->status === 'investigating' ? 'selected' : '' }}>Investigating</option>
                                <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="dismissed" {{ $report->status === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <select name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="low" {{ $report->priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $report->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $report->priority === 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ $report->priority === 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                            <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">Unassigned</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $report->assigned_to == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Internal notes...">{{ $report->admin_notes }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Resolution</label>
                            <textarea name="resolution" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Resolution details...">{{ $report->resolution }}</textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                            Update Report
                        </button>
                    </form>
                </div>

                <!-- Report Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Report Information</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-500">Current Status:</span>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'investigating' => 'bg-blue-100 text-blue-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'dismissed' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$report->status] }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>

                        @if($report->assignedAdmin)
                            <div>
                                <span class="text-gray-500">Assigned To:</span>
                                <p class="mt-1 font-medium">{{ $report->assignedAdmin->name }}</p>
                            </div>
                        @endif

                        @if($report->resolved_at)
                            <div>
                                <span class="text-gray-500">Resolved At:</span>
                                <p class="mt-1">{{ $report->resolved_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif

                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <p class="mt-1">{{ $report->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('select[name="action"]').addEventListener('change', function() {
    const durationField = document.getElementById('duration-field');
    if (this.value === 'suspend') {
        durationField.classList.remove('hidden');
    } else {
        durationField.classList.add('hidden');
    }
});
</script>
@endsection
