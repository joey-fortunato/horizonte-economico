<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::withCount('articles')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role', 'status', 'slug', 'title'])
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role,
                'roleLabel' => $u->role()?->label() ?? $u->role,
                'status' => $u->status,
                'title' => $u->title,
                'articles' => $u->articles_count,
                'isSelf' => $u->id === $request->user()->id,
            ]);

        return Inertia::render('admin/users/index', [
            'users' => $users,
            'roles' => collect(UserRole::cases())->map(fn ($r) => ['value' => $r->value, 'label' => $r->label()]),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'title' => ['nullable', 'string', 'max:120'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'title' => $data['title'] ?? null,
            'status' => 'invited',
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(4)),
            'password' => Hash::make(Str::random(32)),
        ]);

        return back()->with('flash', 'Convite criado.');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
            'status' => ['required', Rule::in(['active', 'invited', 'disabled'])],
        ]);
        $user->update($data);

        return back()->with('flash', 'Utilizador actualizado.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return back()->with('flash', 'Utilizador removido.');
    }
}
