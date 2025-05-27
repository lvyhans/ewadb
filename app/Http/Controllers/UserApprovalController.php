<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserApprovalController extends Controller
{
    /**
     * Display pending registrations
     */
    public function index()
    {
        $pendingUsers = User::where('approval_status', 'pending')
            ->with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.user-approvals.index', compact('pendingUsers'));
    }

    /**
     * Show detailed view of a pending user
     */
    public function show(User $user)
    {
        if ($user->approval_status !== 'pending') {
            return redirect()->route('user-approvals.index')
                ->with('error', 'This user is not pending approval.');
        }

        return view('admin.user-approvals.show', compact('user'));
    }

    /**
     * Approve a user
     */
    public function approve(Request $request, User $user)
    {
        if ($user->approval_status !== 'pending') {
            return back()->with('error', 'This user is not pending approval.');
        }

        $user->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('user-approvals.index')
            ->with('success', 'User has been approved successfully!');
    }

    /**
     * Reject a user
     */
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($user->approval_status !== 'pending') {
            return back()->with('error', 'This user is not pending approval.');
        }

        $user->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('user-approvals.index')
            ->with('success', 'User has been rejected.');
    }

    /**
     * Download a document
     */
    public function downloadDocument(User $user, $document)
    {
        $allowedDocuments = [
            'company_registration_certificate',
            'gst_certificate',
            'pan_card',
            'address_proof',
            'bank_statement'
        ];

        if (!in_array($document, $allowedDocuments)) {
            abort(404);
        }

        $filePath = $user->$document;
        
        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('private')->download($filePath);
    }
}
