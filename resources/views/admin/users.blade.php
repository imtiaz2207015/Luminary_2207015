@extends('layouts.admin')
@section('title', 'Users')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">All Users</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">{{ $users->total() }} registered users</p>
    </div>
</div>

<div class="glass-card p-0">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Capsules</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td style="color:#8b95a8;">{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $user->avatar_url }}" style="width:34px; height:34px; border-radius:50%; border:2px solid rgba(201,168,76,0.2);" alt="">
                            <span style="font-weight:600; font-size:0.88rem;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:#8b95a8; font-size:0.85rem;">{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge-gold">👑 Admin</span>
                        @else
                            <span style="color:#8b95a8; font-size:0.78rem;">User</span>
                        @endif
                    </td>
                    <td style="color:#8b95a8;">{{ $user->capsules_count }}</td>
                    <td style="color:#8b95a8; font-size:0.82rem;">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.users.toggleRole', $user) }}">
                                @csrf
                                <button class="btn btn-ghost btn-sm" style="font-size:0.78rem;">
                                    {{ $user->role === 'admin' ? 'Make User' : 'Make Admin' }}
                                </button>
                            </form>
                            @if($user->role !== 'admin')
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Delete user and all their data?')" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger-soft btn-sm">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->links() }}</div>
</div>
@endsection