@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Report Security Issue</h1>
            <p class="text-gray-600 mb-6">Help us keep our platform safe by reporting security concerns</p>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('security.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Report Type <span class="text-red-500">*</span>
                    </label>
                    <select id="report_type"
                            name="report_type"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select a report type...</option>
                        @foreach($reportTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('report_type') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="reported_user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Report User (Optional)
                    </label>
                    <select id="reported_user_id"
                            name="reported_user_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select a user (if applicable)...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('reported_user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Select the user involved in this security issue, if any</p>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="subject"
                           name="subject"
                           value="{{ old('subject') }}"
                           required
                           maxlength="255"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Brief summary of the issue">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Detailed Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="6"
                              required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Please provide as much detail as possible about the security issue you encountered. Include:
- What happened?
- When did it happen?
- What were you doing when it occurred?
- Any other relevant information...">{{ old('description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Minimum 20 characters</p>
                </div>

                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                        URL / Location
                    </label>
                    <input type="url"
                           id="url"
                           name="url"
                           value="{{ old('url') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="https://example.com/page-where-issue-occurred">
                    <p class="mt-1 text-sm text-gray-500">Where did the issue occur?</p>
                </div>

                <div>
                    <label for="evidence" class="block text-sm font-medium text-gray-700 mb-2">
                        Evidence (Screenshot/Document)
                    </label>
                    <input type="file"
                           id="evidence"
                           name="evidence"
                           accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">
                        Upload screenshots or documents to support your report. Max size: 10MB. Formats: JPG, PNG, PDF
                    </p>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-700">
                            <p><strong>What happens next?</strong></p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Your report will be reviewed by our security team</li>
                                <li>We'll investigate the issue and take appropriate action</li>
                                <li>You'll be notified of any updates via email</li>
                                <li>Critical issues are prioritized for immediate review</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('security.reports.index') }}" class="text-gray-600 hover:text-gray-800">
                        View My Reports
                    </a>
                    <button type="submit"
                            class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition duration-200 shadow-lg">
                        Submit Security Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
