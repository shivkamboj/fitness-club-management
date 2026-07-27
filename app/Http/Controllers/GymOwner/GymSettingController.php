<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use App\Models\GymBranch;
use App\Models\GymSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GymSettingController extends Controller
{
    /**
     * Resolve gym owner id (works for both owners and trainers).
     */
    private function gymOwnerId(): int
    {
        $user = Auth::user();
        return $user->isGymOwner() ? $user->id : $user->gym_owner_id;
    }

    /**
     * Display the settings page with all tabs.
     */
    public function index(Request $request)
    {
        $gymOwnerId = $this->gymOwnerId();
        $settings   = GymSetting::allFor($gymOwnerId);
        $branches   = GymBranch::where('gym_owner_id', $gymOwnerId)->latest()->get();
        $activeTab  = $request->query('tab', 'profile');

        return view('gym-owner.settings.index', compact('settings', 'branches', 'activeTab'));
    }

    /**
     * Save Gym Profile settings.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'gym_name'    => 'required|string|max:255',
            'gym_email'   => 'nullable|email|max:255',
            'gym_phone'   => 'nullable|string|max:30',
            'gym_address' => 'nullable|string|max:500',
            'gym_city'    => 'nullable|string|max:100',
            'gym_state'   => 'nullable|string|max:100',
            'gym_pincode' => 'nullable|string|max:20',
            'gym_website' => 'nullable|url|max:255',
            'gym_about'   => 'nullable|string|max:2000',
        ]);

        $gymOwnerId = $this->gymOwnerId();

        foreach (['gym_name','gym_email','gym_phone','gym_address','gym_city','gym_state','gym_pincode','gym_website','gym_about'] as $key) {
            GymSetting::setValue($gymOwnerId, $key, $request->input($key));
        }

        return back()->with('success', 'Gym profile updated successfully.')->withInput(['tab' => 'profile']);
    }

    /**
     * Upload Gym Logo.
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'gym_logo' => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ]);

        $gymOwnerId = $this->gymOwnerId();

        // Delete old logo if exists
        $oldLogo = GymSetting::getValue($gymOwnerId, 'gym_logo');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        $path = $request->file('gym_logo')->store('gym-logos', 'public');
        GymSetting::setValue($gymOwnerId, 'gym_logo', $path);

        return back()->with('success', 'Gym logo updated successfully.')->withInput(['tab' => 'logo']);
    }

    /**
     * Remove Gym Logo.
     */
    public function removeLogo()
    {
        $gymOwnerId = $this->gymOwnerId();
        $logo = GymSetting::getValue($gymOwnerId, 'gym_logo');
        if ($logo && Storage::disk('public')->exists($logo)) {
            Storage::disk('public')->delete($logo);
        }
        GymSetting::setValue($gymOwnerId, 'gym_logo', null);

        return back()->with('success', 'Logo removed.')->withInput(['tab' => 'logo']);
    }

    /**
     * Save Tax settings.
     */
    public function updateTaxes(Request $request)
    {
        $request->validate([
            'tax_enabled'    => 'nullable|in:0,1',
            'tax_name'       => 'nullable|string|max:100',
            'tax_rate'       => 'nullable|numeric|min:0|max:100',
            'tax_number'     => 'nullable|string|max:50',
            'secondary_tax_name' => 'nullable|string|max:100',
            'secondary_tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $gymOwnerId = $this->gymOwnerId();

        foreach (['tax_enabled','tax_name','tax_rate','tax_number','secondary_tax_name','secondary_tax_rate'] as $key) {
            GymSetting::setValue($gymOwnerId, $key, $request->input($key, ''));
        }

        return back()->with('success', 'Tax settings updated.')->withInput(['tab' => 'taxes']);
    }

    /**
     * Save Currency settings.
     */
    public function updateCurrency(Request $request)
    {
        $request->validate([
            'currency_code'   => 'required|string|max:5',
            'currency_symbol' => 'required|string|max:5',
            'currency_position' => 'required|in:before,after',
        ]);

        $gymOwnerId = $this->gymOwnerId();

        foreach (['currency_code','currency_symbol','currency_position'] as $key) {
            GymSetting::setValue($gymOwnerId, $key, $request->input($key));
        }

        return back()->with('success', 'Currency settings updated.')->withInput(['tab' => 'currency']);
    }

    /**
     * Save Working Hours & Days settings.
     */
    public function updateWorkingHours(Request $request)
    {
        $request->validate([
            'working_days'     => 'nullable|array',
            'working_days.*'   => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'opening_time'     => 'nullable|date_format:H:i',
            'closing_time'     => 'nullable|date_format:H:i',
            'weekend_opening'  => 'nullable|date_format:H:i',
            'weekend_closing'  => 'nullable|date_format:H:i',
        ]);

        $gymOwnerId = $this->gymOwnerId();

        GymSetting::setValue($gymOwnerId, 'working_days', json_encode($request->input('working_days', [])));
        GymSetting::setValue($gymOwnerId, 'opening_time', $request->input('opening_time'));
        GymSetting::setValue($gymOwnerId, 'closing_time', $request->input('closing_time'));
        GymSetting::setValue($gymOwnerId, 'weekend_opening', $request->input('weekend_opening'));
        GymSetting::setValue($gymOwnerId, 'weekend_closing', $request->input('weekend_closing'));

        return back()->with('success', 'Working hours updated.')->withInput(['tab' => 'hours']);
    }

    /**
     * Store a new branch.
     */
    public function storeBranch(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'nullable|string|max:500',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
        ]);

        GymBranch::create([
            'gym_owner_id' => $this->gymOwnerId(),
            'name'         => $request->name,
            'address'      => $request->address,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'manager_name' => $request->manager_name,
            'status'       => 'active',
        ]);

        return back()->with('success', 'Branch added successfully.')->withInput(['tab' => 'branches']);
    }

    /**
     * Update a branch.
     */
    public function updateBranch(Request $request, GymBranch $branch)
    {
        if ($branch->gym_owner_id !== $this->gymOwnerId()) {
            abort(403);
        }

        $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'nullable|string|max:500',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'status'       => 'required|in:active,inactive',
        ]);

        $branch->update($request->only('name', 'address', 'phone', 'email', 'manager_name', 'status'));

        return back()->with('success', 'Branch updated.')->withInput(['tab' => 'branches']);
    }

    /**
     * Delete a branch.
     */
    public function destroyBranch(GymBranch $branch)
    {
        if ($branch->gym_owner_id !== $this->gymOwnerId()) {
            abort(403);
        }

        $branch->delete();
        return back()->with('success', 'Branch deleted.')->withInput(['tab' => 'branches']);
    }

    /**
     * Save SMS settings.
     */
    public function updateSms(Request $request)
    {
        $request->validate([
            'sms_enabled'   => 'nullable|in:0,1',
            'sms_provider'  => 'nullable|string|max:100',
            'sms_api_key'   => 'nullable|string|max:255',
            'sms_sender_id' => 'nullable|string|max:20',
        ]);

        $gymOwnerId = $this->gymOwnerId();

        foreach (['sms_enabled','sms_provider','sms_api_key','sms_sender_id'] as $key) {
            GymSetting::setValue($gymOwnerId, $key, $request->input($key, ''));
        }

        return back()->with('success', 'SMS settings updated.')->withInput(['tab' => 'sms']);
    }

    /**
     * Save WhatsApp settings.
     */
    public function updateWhatsapp(Request $request)
    {
        $request->validate([
            'whatsapp_enabled'       => 'nullable|in:0,1',
            'whatsapp_api_provider'  => 'nullable|string|max:100',
            'whatsapp_api_key'       => 'nullable|string|max:255',
            'whatsapp_phone_number'  => 'nullable|string|max:20',
        ]);

        $gymOwnerId = $this->gymOwnerId();

        foreach (['whatsapp_enabled','whatsapp_api_provider','whatsapp_api_key','whatsapp_phone_number'] as $key) {
            GymSetting::setValue($gymOwnerId, $key, $request->input($key, ''));
        }

        return back()->with('success', 'WhatsApp settings updated.')->withInput(['tab' => 'whatsapp']);
    }

    /**
     * Download a database backup (simplified — exports settings as JSON).
     */
    public function downloadBackup()
    {
        $gymOwnerId = $this->gymOwnerId();
        $settings   = GymSetting::allFor($gymOwnerId);
        $branches   = GymBranch::where('gym_owner_id', $gymOwnerId)->get()->toArray();

        $data = [
            'exported_at' => now()->toIso8601String(),
            'gym_owner_id' => $gymOwnerId,
            'settings' => $settings,
            'branches' => $branches,
        ];

        $filename = 'gym_backup_' . now()->format('Y_m_d_His') . '.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }
}
