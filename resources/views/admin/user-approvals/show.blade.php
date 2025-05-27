@extends('layouts.app')

@section('title', 'Review Registration')
@section('page-title', 'User Approval Management')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('user-approvals.index') }}" 
                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Pending Registrations
                </a>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                    Pending Approval
                </span>
            </div>
        </div>

        <!-- User Information Card -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-medium text-2xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Registered {{ $user->created_at->format('M d, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <!-- Personal Information -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Personal Information</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="space-y-2">
                                <p><strong>Full Name:</strong> {{ $user->name }}</p>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                            </div>
                        </dd>
                    </div>

                    <!-- Company Information -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Company Information</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p><strong>Company Name:</strong> {{ $user->company_name }}</p>
                                    <p><strong>Registration Number:</strong> {{ $user->company_registration_number }}</p>
                                    <p><strong>GSTIN:</strong> {{ $user->gstin }}</p>
                                    <p><strong>PAN Number:</strong> {{ $user->pan_number }}</p>
                                </div>
                                <div>
                                    <p><strong>Phone:</strong> {{ $user->company_phone }}</p>
                                    <p><strong>Email:</strong> {{ $user->company_email }}</p>
                                    <p><strong>City:</strong> {{ $user->company_city }}</p>
                                    <p><strong>State:</strong> {{ $user->company_state }}</p>
                                    <p><strong>Pincode:</strong> {{ $user->company_pincode }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p><strong>Address:</strong></p>
                                <p class="text-gray-700 mt-1">{{ $user->company_address }}</p>
                            </div>
                        </dd>
                    </div>

                    <!-- Documents -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Uploaded Documents</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $documents = [
                                        'company_registration_certificate' => 'Company Registration Certificate',
                                        'gst_certificate' => 'GST Certificate',
                                        'pan_card' => 'PAN Card',
                                        'address_proof' => 'Address Proof',
                                        'bank_statement' => 'Bank Statement'
                                    ];
                                @endphp

                                @foreach($documents as $field => $label)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div>
                                            <p class="font-medium">{{ $label }}</p>
                                            @if($user->$field)
                                                <p class="text-xs text-green-600">✓ Uploaded</p>
                                            @else
                                                <p class="text-xs text-red-600">✗ Missing</p>
                                            @endif
                                        </div>
                                        @if($user->$field)
                                            <a href="{{ route('user-approvals.download-document', [$user, $field]) }}" 
                                               class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
            <!-- Approve Button -->
            <div class="flex-1">
                <form method="POST" action="{{ route('user-approvals.approve', $user) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to approve this user? They will be able to login immediately.')"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve Registration
                    </button>
                </form>
            </div>

            <!-- Reject Button -->
            <div class="flex-1">
                <button type="button" 
                        onclick="showRejectModal()"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Registration
                </button>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Reject Registration</h3>
                    <button onclick="hideRejectModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('user-approvals.reject', $user) }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Rejection <span class="text-red-500">*</span>
                        </label>
                        <textarea id="rejection_reason" 
                                  name="rejection_reason" 
                                  rows="4" 
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Please provide a clear reason for rejecting this registration..."></textarea>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="hideRejectModal()"
                                class="flex-1 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }
        
        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejection_reason').value = '';
        }
        
        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });
    </script>
@endsection