<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {
    }

    public function index(Request $request): View
    {
        return view('notifications.index');
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        return response()->json([
            'success' => true,
            'unread_count' => $this->notificationService->unreadCount($userId),
        ]);
    }

    public function latest(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 5);
        $limit = max(1, min(10, $limit));

        $userId = $request->user()->id;

        $notifications = $this->notificationService->latest($userId, $limit);

        return response()->json([
            'success' => true,
            'data' => $notifications->map(fn (UserNotification $n) => $this->notificationService->toDto($n)),
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $perPage = (int) $request->integer('per_page', 15);
        $page = (int) $request->integer('page', 1);

        $userId = $request->user()->id;

        $paginator = $this->notificationService->list($userId, $perPage, $page);

        return response()->json([
            'success' => true,
            'data' => $paginator->getCollection()->map(
                fn (UserNotification $n) => $this->notificationService->toDto($n)
            ),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function markRead(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
        ]);

        $ids = $payload['ids'] ?? [];
        $userId = $request->user()->id;

        $updatedCount = $this->notificationService->markAsRead($userId, $ids);
        $unreadCount = $this->notificationService->unreadCount($userId);

        return response()->json([
            'success' => true,
            'updated' => $updatedCount,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $updatedCount = $this->notificationService->markAllAsRead($userId);
        $unreadCount = 0;

        return response()->json([
            'success' => true,
            'updated' => $updatedCount,
            'unread_count' => $unreadCount,
        ]);
    }
}

