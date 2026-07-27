<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Resolve gym owner ID for logged in user (owner or trainer).
     */
    private function getGymOwnerId(): int
    {
        $user = Auth::user();
        return $user->isGymOwner() ? $user->id : ($user->gym_owner_id ?? $user->id);
    }

    /**
     * Display leads list with filter and summary stats.
     */
    public function index(Request $request)
    {
        $gymOwnerId = $this->getGymOwnerId();

        $query = Lead::where('gym_owner_id', $gymOwnerId)
            ->with(['assignedTrainer', 'creator'])
            ->latest();

        // Search by name, phone, email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by source
        if ($request->filled('source') && in_array($request->source, Lead::SOURCES, true)) {
            $query->where('source', $request->source);
        }

        // Filter by status
        if ($request->filled('status') && in_array($request->status, Lead::STATUSES, true)) {
            $query->where('status', $request->status);
        }

        $leads = $query->paginate(15)->withQueryString();

        // Stats calculation
        $allLeads = Lead::where('gym_owner_id', $gymOwnerId)->get();
        $stats = [
            'total'     => $allLeads->count(),
            'new'       => $allLeads->where('status', Lead::STATUS_NEW)->count(),
            'follow_up' => $allLeads->where('status', Lead::STATUS_FOLLOW_UP)->count(),
            'converted' => $allLeads->where('status', Lead::STATUS_CONVERTED)->count(),
            'lost'      => $allLeads->where('status', Lead::STATUS_LOST)->count(),
        ];

        // Trainers list for assignment dropdown
        $trainers = Trainer::where('gym_owner_id', $gymOwnerId)
            ->where('status', 'active')
            ->get();

        $sources = Lead::SOURCES;
        $statuses = Lead::STATUSES;

        return view('gym-owner.leads.index', compact('leads', 'stats', 'trainers', 'sources', 'statuses'));
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:30',
            'email'          => 'nullable|email|max:255',
            'source'         => 'required|string|in:' . implode(',', Lead::SOURCES),
            'status'         => 'required|string|in:' . implode(',', Lead::STATUSES),
            'follow_up_date' => 'nullable|date',
            'notes'          => 'nullable|string',
            'assigned_to'    => 'nullable|exists:users,id',
        ]);

        $gymOwnerId = $this->getGymOwnerId();

        Lead::create([
            'gym_owner_id'   => $gymOwnerId,
            'name'           => $request->name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'source'         => $request->source,
            'status'         => $request->status,
            'follow_up_date' => $request->follow_up_date,
            'notes'          => $request->notes,
            'assigned_to'    => $request->assigned_to ?: null,
            'created_by'     => Auth::id(),
        ]);

        return redirect()->route('gym-owner.leads.index')
            ->with('success', 'Lead enquiry created successfully.');
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $gymOwnerId = $this->getGymOwnerId();
        if ((int)$lead->gym_owner_id !== (int)$gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:30',
            'email'          => 'nullable|email|max:255',
            'source'         => 'required|string|in:' . implode(',', Lead::SOURCES),
            'status'         => 'required|string|in:' . implode(',', Lead::STATUSES),
            'follow_up_date' => 'nullable|date',
            'notes'          => 'nullable|string',
            'assigned_to'    => 'nullable|exists:users,id',
        ]);

        $lead->update([
            'name'           => $request->name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'source'         => $request->source,
            'status'         => $request->status,
            'follow_up_date' => $request->follow_up_date,
            'notes'          => $request->notes,
            'assigned_to'    => $request->assigned_to ?: null,
        ]);

        return redirect()->route('gym-owner.leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    /**
     * Quick status update for a lead.
     */
    public function updateStatus(Request $request, Lead $lead)
    {
        $gymOwnerId = $this->getGymOwnerId();
        if ((int)$lead->gym_owner_id !== (int)$gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|string|in:' . implode(',', Lead::STATUSES),
        ]);

        $lead->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Lead status updated to ' . $request->status . '.');
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(Lead $lead)
    {
        $gymOwnerId = $this->getGymOwnerId();
        if ((int)$lead->gym_owner_id !== (int)$gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $lead->delete();

        return redirect()->route('gym-owner.leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}
