<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use App\Models\GymSetting;
use App\Models\User;
use App\Models\WhatsAppTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppNotificationController extends Controller
{
    private function getGymOwnerId(): int
    {
        $user = Auth::user();
        return $user->isGymOwner() ? $user->id : ($user->gym_owner_id ?? $user->id);
    }

    /**
     * Display WhatsApp Notification Events & Template Manager
     */
    public function index(Request $request)
    {
        $gymOwnerId = $this->getGymOwnerId();
        $templates = WhatsAppTemplate::getTemplatesForGym($gymOwnerId);

        // Fetch gym members for quick direct WhatsApp messaging
        $members = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->latest()
            ->get();

        $gymName = GymSetting::getValue($gymOwnerId, 'gym_name', Auth::user()->gym_name ?? 'GymForce Fitness');

        return view('gym-owner.notifications.whatsapp', compact('templates', 'members', 'gymName'));
    }

    /**
     * Update WhatsApp Event Templates
     */
    public function updateTemplates(Request $request)
    {
        $gymOwnerId = $this->getGymOwnerId();
        $defaultEvents = WhatsAppTemplate::getDefaultEvents();

        $templatesData = $request->input('templates', []);

        foreach ($defaultEvents as $key => $meta) {
            $data = $templatesData[$key] ?? [];
            $isEnabled = isset($data['is_enabled']) && (string)$data['is_enabled'] === '1';
            $templateText = !empty($data['message_template']) ? $data['message_template'] : $meta['template'];

            WhatsAppTemplate::updateOrCreate(
                [
                    'gym_owner_id' => $gymOwnerId,
                    'event_key'    => $key,
                ],
                [
                    'event_title'      => $meta['title'],
                    'is_enabled'       => $isEnabled,
                    'message_template' => $templateText,
                ]
            );
        }

        return back()->with('success', 'WhatsApp notification event templates updated successfully.');
    }

    /**
     * Generate WhatsApp Direct Message URL for a member and specific event
     */
    public function generateMessage(Request $request)
    {
        $request->validate([
            'member_id'    => 'required|exists:users,id',
            'event_key'    => 'required|string',
            'offer_details'=> 'nullable|string',
        ]);

        $gymOwnerId = $this->getGymOwnerId();
        $member = User::findOrFail($request->member_id);
        $templates = WhatsAppTemplate::getTemplatesForGym($gymOwnerId);

        $eventKey = $request->event_key;
        if (!isset($templates[$eventKey])) {
            return response()->json(['error' => 'Invalid event type'], 422);
        }

        $template = $templates[$eventKey];
        $gymName = GymSetting::getValue($gymOwnerId, 'gym_name', Auth::user()->gym_name ?? 'GymForce');

        // Dynamic replacements
        $replacements = [
            '{member_name}'   => $member->full_name,
            '{gym_name}'      => $gymName,
            '{plan_name}'     => 'Regular Plan',
            '{joining_date}'  => $member->joining_date ? $member->joining_date->format('d M Y') : date('d M Y'),
            '{due_date}'      => date('d M Y', strtotime('+7 days')),
            '{amount}'        => '₹1,500',
            '{offer_details}' => $request->offer_details ?: 'Flat 20% OFF on Annual Memberships this week!',
            '{workout_plan}'  => 'Upper Body & Cardio Routine',
        ];

        $message = str_replace(array_keys($replacements), array_values($replacements), $template['message_template']);

        // Clean phone number for WhatsApp
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($member->phone ?? ''));
        if (strlen($cleanPhone) === 10) {
            $cleanPhone = '91' . $cleanPhone;
        }

        $whatsappUrl = 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($message);

        return response()->json([
            'success'     => true,
            'whatsapp_url'=> $whatsappUrl,
            'message'     => $message,
            'phone'       => $member->phone,
        ]);
    }
}
