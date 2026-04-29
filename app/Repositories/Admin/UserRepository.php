<?php

namespace App\Repositories\Admin;

use App\Enums\UserStatus;
use App\Models\Core\Profile;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null, array $filters = []): LengthAwarePaginator
    {
        $query = User::query()
            ->with(['roles', 'profile']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('profile', fn ($p) => $p->where('display_name', 'ilike', "%{$search}%"))
                    ->orWhere('username', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('phone', 'ilike', "%{$search}%");
            });
        }

        // Apply user type filter
        if (!empty($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        // Apply status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        // Apply created from filter
        if (!empty($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }

        // Apply created to filter
        if (!empty($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return User::with('profile')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int|string $id): ?User
    {
        return User::with(['roles', 'profile'])->find($id);
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['password'])) {
                $data['salt'] = base64_encode($data['password']);
            }

            $user = new User([
                'username'      => $data['username'],
                'email'         => $data['email'],
                'password_hash' => $data['password'],
                'salt'          => $data['salt'] ?? null,
                'user_type'     => $data['user_type'] ?? null,
                'phone'         => $data['contact_no'] ?? null,
                'language'      => $data['language'] ?? 'en',
                'status'        => isset($data['status'])
                    ? ($data['status'] ? UserStatus::Active : UserStatus::Inactive)
                    : UserStatus::Active,
            ]);
            $user->id = (string) Str::uuid();
            $user->save();

            $nameParts = $this->splitName($data['name'] ?? '');
            Profile::create([
                'id'           => $user->id,
                'first_name'   => $nameParts['first'],
                'last_name'    => $nameParts['last'],
                'display_name' => $data['name'] ?? null,
                'address'      => $data['address'] ?? null,
                'contact_no'   => $data['contact_no'] ?? null,
                'language'     => $data['language'] ?? 'en',
            ]);

            return $user->load(['roles', 'profile']);
        });
    }

    /**
     * Update user
     *
     * @param int $id
     * @param array $data
     * @return User
     */
    public function update(int|string $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->findById($id);

            if (isset($data['password']) && !empty($data['password'])) {
                $data['salt'] = base64_encode($data['password']);
            } else {
                unset($data['password'], $data['salt']);
            }

            $userUpdate = [
                'username'  => $data['username'] ?? $user->username,
                'email'     => $data['email'] ?? $user->email,
                'user_type' => $data['user_type'] ?? $user->user_type,
                'phone'     => $data['contact_no'] ?? $user->phone,
                'language'  => $data['language'] ?? $user->language,
                'status'    => isset($data['status'])
                    ? ($data['status'] ? UserStatus::Active : UserStatus::Inactive)
                    : $user->status,
            ];

            if (isset($data['password'])) {
                $userUpdate['password_hash'] = $data['password'];
                $userUpdate['salt'] = $data['salt'];
            }

            $user->update($userUpdate);

            $nameParts = $this->splitName($data['name'] ?? $user->name ?? '');
            $profileData = [
                'first_name'   => $nameParts['first'],
                'last_name'    => $nameParts['last'],
                'display_name' => $data['name'] ?? $user->profile?->display_name,
                'address'      => $data['address'] ?? $user->profile?->address,
                'contact_no'   => $data['contact_no'] ?? $user->profile?->contact_no,
                'language'     => $data['language'] ?? $user->language,
            ];

            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                Profile::create(array_merge(['id' => $user->id], $profileData));
            }

            return $user->fresh(['roles', 'profile']);
        });
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return bool
     */
    public function delete(int|string $id): bool
    {
        $user = $this->findById($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    /**
     * Check if user exists by email
     *
     * @param string $email
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByEmail(string $email, int|string|null $excludeId = null): bool
    {
        $query = User::where('email', $email);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if user exists by username
     *
     * @param string $username
     * @param int|null $excludeId
     * @return bool
     */
    public function existsByUsername(string $username, int|string|null $excludeId = null): bool
    {
        $query = User::where('username', $username);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Assign role to user
     *
     * @param int $userId
     * @param int $roleId
     * @return void
     */
    public function assignRole(int|string $userId, int $roleId): void
    {
        $user = $this->findById($userId);

        if ($user) {
            $user->syncRoles([$roleId]);
        }
    }

    private function splitName(string $name): array
    {
        $parts = explode(' ', trim($name), 2);
        return ['first' => $parts[0] ?? '', 'last' => $parts[1] ?? ''];
    }
}
