@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('security.reports.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to My Reports
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Security Report #{{ $report->id }}</h1>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Status</span>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'investigating' => 'bg-blue-100 text-blue-800',
                                'resolved' => 'bg-green-100 text-green-800',
                                'dismissed' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <div class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-500">Priority</span>
                        @php
                            $priorityColors = [
                                'critical' => 'bg-red-100 text-red-800',
                                'high' => 'bg-orange-100 text-orange-800',
                                'medium' => 'bg-yellow-100 text-yellow-800',
                                'low' => 'bg-green-100 text-green-800',
                            ];
                        @endphp
                        <div class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $priorityColors[$report->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($report->priority) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-500">Submitted</span>
                        <p class="mt-1 text-sm text-gray-900">{{ $report->created_at->format('M d, Y') }}</p>
                    </div>

                    @if($report->resolved_at)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Resolved</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $report->resolved_at->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>

                <hr>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Report Type</h3>
                    <p class="text-gray-900">{{ str_replace('_', ' ', ucwords($report->report_type)) }}</p>
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

                @if($report->reported_user_id && $report->reportedUser)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Reported User</h3>
                        <p class="text-gray-900">{{ $report->reportedUser->name }} ({{ $report->reportedUser->email }})</p>
                    </div>
                @endif

                @if($report->url)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">URL / Location</h3>
                        <a href="{{ $report->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all">
                            {{ $report->url }}
                        </a>
                    </div>
                @endif

                @if($report->evidence)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Evidence</h3>
                        <p class="text-sm text-gray-600">Evidence file submitted (viewable by admin only)</p>
                    </div>
                @endif

                @if($report->assignedAdmin)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Assigned To</h3>
                        <p class="text-gray-900">{{ $report->assignedAdmin->name }}</p>
                    </div>
                @endif

                @if($report->resolution)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-green-900 mb-2">Resolution</h3>
                        <p class="text-green-800">{{ $report->resolution }}</p>
                    </div>
                @endif

                @if($report->status === 'pending')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-yellow-700">
                                Your report is pending review by our security team. We'll notify you of any updates.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
