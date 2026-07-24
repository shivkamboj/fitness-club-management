<?php

namespace App\Services;

use App\Models\Trainer;
use App\Models\User;
use App\Traits\SendsMail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class TrainerService
{
    use SendsMail;

    public function __construct(
        private readonly TrainerImageService $imageService
    ) {}

    /**
     * @param  array{search?: string|null, status?: string|null, per_page?: int|null}  $filters
     */
    public function listForOwner(User $owner, array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 10);
        $perPage = max(5, min($perPage, 50));

        return Trainer::query()
            ->ownedBy($owner->id)
            ->search($filters['search'] ?? null)
            ->filterStatus($filters['status'] ?? null)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{trainer: Trainer, plain_password: string}
     */
    public function create(User $owner, array $data): array
    {
        $plainPassword = $data['password'] ?? $this->generatePassword();

        $trainer = DB::transaction(function () use ($owner, $data, $plainPassword) {
            $profilePath = null;
            $backgroundPath = null;

            if (($data['profile_image'] ?? null) instanceof UploadedFile) {
                $profilePath = $this->imageService->storeProfile($data['profile_image']);
            }

            if (($data['background_image'] ?? null) instanceof UploadedFile) {
                $backgroundPath = $this->imageService->storeBackground($data['background_image']);
            }

            $firstName = trim((string) $data['first_name']);
            $lastName = trim((string) $data['last_name']);

            return Trainer::create([
                'name' => trim($firstName.' '.$lastName),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower(trim((string) $data['email'])),
                'phone' => trim((string) $data['phone']),
                'password' => $plainPassword,
                'role' => User::ROLE_TRAINER,
                'gym_owner_id' => $owner->id,
                'gym_name' => $owner->gym_name,
                'gender' => $data['gender'] ?? null,
                'dob' => $data['dob'] ?? null,
                'joining_date' => $data['joining_date'] ?? null,
                'specialization' => $data['specialization'] ?? null,
                'experience' => $data['experience'] ?? null,
                'certifications' => $data['certifications'] ?? null,
                'skills' => $data['skills'] ?? null,
                'profile_image' => $profilePath,
                'background_image' => $backgroundPath,
                'status' => $data['status'] ?? Trainer::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]);
        });

        $this->sendWelcomeEmail($trainer, $owner, $plainPassword);

        return [
            'trainer' => $trainer->fresh(),
            'plain_password' => $plainPassword,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Trainer $trainer, array $data): Trainer
    {
        return DB::transaction(function () use ($trainer, $data) {
            $firstName = trim((string) ($data['first_name'] ?? $trainer->first_name));
            $lastName = trim((string) ($data['last_name'] ?? $trainer->last_name));

            $payload = [
                'name' => trim($firstName.' '.$lastName),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower(trim((string) ($data['email'] ?? $trainer->email))),
                'phone' => trim((string) ($data['phone'] ?? $trainer->phone)),
                'gender' => $data['gender'] ?? null,
                'dob' => $data['dob'] ?? null,
                'joining_date' => $data['joining_date'] ?? null,
                'specialization' => $data['specialization'] ?? null,
                'experience' => $data['experience'] ?? null,
                'certifications' => $data['certifications'] ?? null,
                'skills' => $data['skills'] ?? null,
                'status' => $data['status'] ?? $trainer->status,
            ];

            if (! empty($data['password'])) {
                $payload['password'] = $data['password'];
            }

            if (! empty($data['remove_profile_image'])) {
                $this->imageService->delete($trainer->profile_image);
                $payload['profile_image'] = null;
            } elseif (($data['profile_image'] ?? null) instanceof UploadedFile) {
                $this->imageService->delete($trainer->profile_image);
                $payload['profile_image'] = $this->imageService->storeProfile($data['profile_image']);
            }

            if (! empty($data['remove_background_image'])) {
                $this->imageService->delete($trainer->background_image);
                $payload['background_image'] = null;
            } elseif (($data['background_image'] ?? null) instanceof UploadedFile) {
                $this->imageService->delete($trainer->background_image);
                $payload['background_image'] = $this->imageService->storeBackground($data['background_image']);
            }

            $trainer->update($payload);

            return $trainer->fresh();
        });
    }

    public function delete(Trainer $trainer): void
    {
        DB::transaction(function () use ($trainer): void {
            $this->imageService->delete($trainer->profile_image);
            $this->imageService->delete($trainer->background_image);

            // Free unique email/phone so the owner can re-add the same trainer later.
            $trainer->forceFill([
                'profile_image' => null,
                'background_image' => null,
                'email' => $trainer->email.'.deleted.'.$trainer->id,
                'phone' => $trainer->phone
                    ? $trainer->phone.'.deleted.'.$trainer->id
                    : null,
            ])->save();

            $trainer->delete();
        });
    }

    public function toggleStatus(Trainer $trainer, string $status): Trainer
    {
        $trainer->update([
            'status' => $status === Trainer::STATUS_INACTIVE
                ? Trainer::STATUS_INACTIVE
                : Trainer::STATUS_ACTIVE,
        ]);

        return $trainer->fresh();
    }

    public function findOwnedOrFail(User $owner, int $trainerId): Trainer
    {
        return Trainer::query()
            ->ownedBy($owner->id)
            ->findOrFail($trainerId);
    }

    public function generatePassword(int $length = 12): string
    {
        return Str::password($length, symbols: false);
    }

    private function sendWelcomeEmail(Trainer $trainer, User $owner, string $plainPassword): void
    {
        $gymName = $owner->gym_name ?: config('app.name');

        try {
            $this->sendMail([
                'to' => $trainer->email,
                'subject' => 'Welcome to '.$gymName,
                'view' => 'emails.trainer-welcome',
                'data' => [
                    'subject' => 'Welcome to '.$gymName,
                    'trainerName' => $trainer->full_name,
                    'gymName' => $gymName,
                    'email' => $trainer->email,
                    'password' => $plainPassword,
                    'loginUrl' => route('login'),
                ],
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
