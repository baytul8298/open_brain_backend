<?php

namespace App\Repositories\Admin\Contracts;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * Get all users with pagination
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null, array $filters = []): LengthAwarePaginator;

    /**
     * Get all users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int|string $id): ?User;

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Update user
     *
     * @param int|string $id
     * @param array $data
     * @return User
     */
    public function update(int|string $id, array $data): User;

    /**
     * Delete user
     *
     * @param int|string $id
     * @return bool
     */
    public function delete(int|string $id): bool;

    /**
     * Check if user exists by email
     *
     * @param string $email
     * @param int|string|null $excludeId
     * @return bool
     */
    public function existsByEmail(string $email, int|string|null $excludeId = null): bool;

    /**
     * Check if user exists by username
     *
     * @param string $username
     * @param int|string|null $excludeId
     * @return bool
     */
    public function existsByUsername(string $username, int|string|null $excludeId = null): bool;

    /**
     * Assign role to user
     *
     * @param int|string $userId
     * @param int $roleId
     * @return void
     */
    public function assignRole(int|string $userId, int $roleId): void;
}
