<?php

namespace App\Http\Controllers;

use App\Http\Requests\GymOwner\StoreTrainerRequest;
use App\Http\Requests\GymOwner\UpdateTrainerRequest;
use App\Http\Resources\TrainerResource;
use App\Models\Trainer;
use App\Services\TrainerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainerController extends Controller
{
    public function __construct(
        private readonly TrainerService $trainerService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $this->authorize('viewAny', Trainer::class);

        if ($request->expectsJson() || $request->ajax()) {
            $paginator = $this->trainerService->listForOwner($request->user(), [
                'search' => $request->string('search')->toString(),
                'status' => $request->string('status')->toString(),
                'per_page' => $request->integer('per_page', 10),
            ]);

            return response()->json([
                'success' => true,
                'data' => TrainerResource::collection($paginator->items()),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
            ]);
        }

        return view('admin.trainers.index');
    }

    public function store(StoreTrainerRequest $request): JsonResponse
    {
        $this->authorize('create', Trainer::class);

        $result = $this->trainerService->create($request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Trainer created successfully.',
            'data' => new TrainerResource($result['trainer']),
            'credentials' => [
                'email' => $result['trainer']->email,
                'password' => $result['plain_password'],
            ],
        ], 201);
    }

    public function show(Request $request, int $trainer): JsonResponse
    {
        $model = $this->trainerService->findOwnedOrFail($request->user(), $trainer);
        $this->authorize('view', $model);

        return response()->json([
            'success' => true,
            'data' => new TrainerResource($model),
        ]);
    }

    public function update(UpdateTrainerRequest $request, int $trainer): JsonResponse
    {
        $model = $this->trainerService->findOwnedOrFail($request->user(), $trainer);
        $this->authorize('update', $model);

        $updated = $this->trainerService->update($model, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Trainer updated successfully.',
            'data' => new TrainerResource($updated),
        ]);
    }

    public function destroy(Request $request, int $trainer): JsonResponse
    {
        $model = $this->trainerService->findOwnedOrFail($request->user(), $trainer);
        $this->authorize('delete', $model);

        $this->trainerService->delete($model);

        return response()->json([
            'success' => true,
            'message' => 'Trainer deleted successfully.',
        ]);
    }

    public function updateStatus(Request $request, int $trainer): JsonResponse
    {
        $model = $this->trainerService->findOwnedOrFail($request->user(), $trainer);
        $this->authorize('updateStatus', $model);

        $validated = $request->validate([
            'status' => ['required', 'in:active,inactive'],
        ]);

        $updated = $this->trainerService->toggleStatus($model, $validated['status']);

        $label = $updated->status === Trainer::STATUS_ACTIVE ? 'activated' : 'deactivated';

        // In-app notification (Trainer activated/deactivated)
        $this->sendNotification($updated->id, [
            'title' => 'Trainer '.$label,
            'message' => 'Your trainer account for '.$updated->gym_name.' has been '.$label.'.',
            'type' => $updated->status === Trainer::STATUS_ACTIVE ? 'success' : 'warning',
            'module' => 'Trainer',
            'reference_id' => $updated->id,
        ]);

        // Notify gym owner as well
        if ($updated->gym_owner_id) {
            $this->sendNotification($updated->gym_owner_id, [
                'title' => 'Trainer status updated',
                'message' => $updated->full_name.' has been '.$label.' for '.$updated->gym_name.'.',
                'type' => 'information',
                'module' => 'Gym Owner',
                'reference_id' => $updated->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Trainer {$label} successfully.",
            'data' => new TrainerResource($updated),
        ]);
    }

    public function generatePassword(): JsonResponse
    {
        $this->authorize('create', Trainer::class);

        return response()->json([
            'success' => true,
            'password' => $this->trainerService->generatePassword(),
        ]);
    }
}
