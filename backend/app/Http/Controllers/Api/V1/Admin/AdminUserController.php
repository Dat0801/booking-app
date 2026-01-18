<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query()->with('roles');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('is_active')) {
            $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
            if (! is_null($isActive)) {
                $query->where('is_active', $isActive);
            }
        }

        if ($request->filled('role')) {
            $role = $request->string('role')->toString();
            $query->whereHas('roles', function ($rq) use ($role) {
                $rq->where('name', $role);
            });
        }

        if ($request->boolean('with_deleted')) {
            $query->withTrashed();
        }

        $users = $query
            ->withCount(['orders', 'bookings'])
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($users);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::withTrashed()
            ->with(['roles'])
            ->withCount(['orders', 'bookings'])
            ->findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $id],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $user = User::withTrashed()->findOrFail($id);

        $user->fill($data);
        $user->save();

        $user->load('roles');

        return response()->json($user);
    }

    public function updateRoles(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'roles' => ['required', 'array'],
            'roles.*' => ['string', 'distinct'],
        ]);

        $user = User::withTrashed()->findOrFail($id);

        $roleIds = Role::query()
            ->whereIn('name', $data['roles'])
            ->pluck('id')
            ->all();

        $user->roles()->sync($roleIds);

        $user->load('roles');

        return response()->json($user);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(null, 204);
    }

    public function restore(int $id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
        }

        $user->load('roles');

        return response()->json($user);
    }
}

