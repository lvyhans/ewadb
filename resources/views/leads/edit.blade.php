@extends('layouts.app')

@section('page-title', 'Edit Lead')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600/10 to-purple-600/10 p-6 border-b border-white/20">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Lead</h1>
                    <p class="text-gray-600 mt-1">Update lead information</p>
                </div>
                <a href="{{ route('leads.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                    Back to Leads
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Edit Lead Form</h3>
                <p class="text-gray-500 mb-4">Lead ID: {{ $id }}</p>
                <p class="text-gray-500">The edit form will be implemented here with all lead fields for updating existing lead information.</p>
            </div>
        </div>
    </div>
</div>
@endsection
