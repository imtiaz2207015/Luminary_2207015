@extends('layouts.app')
@section('title', 'Edit Capsule')
@section('content')

<div class="topbar">
    <div>
        <div class="topbar-title">Edit Capsule</div>
        <p style="color:#8b95a8; font-size:0.85rem; margin:0;">Only available for draft capsules.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-4">
            <form method="POST" action="{{ route('capsules.update', $capsule) }}">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control form-control-lg"
                           value="{{ old('title', $capsule->title) }}" required>
                    @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="10">{{ old('content', $capsule->content) }}</textarea>
                    @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Unlock Date</label>
                        <input type="date" name="unlock_date" class="form-control"
                               value="{{ old('unlock_date', $capsule->unlock_date->format('Y-m-d')) }}"
                               min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Visibility</label>
                        <select name="visibility" class="form-select">
                            @foreach(['only_me' => '🔒 Only Me', 'friends' => '👥 Friends Only', 'public' => '🌍 Public'] as $val => $label)
                                <option value="{{ $val }}" {{ old('visibility', $capsule->visibility) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('capsules.show', $capsule) }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-gold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection