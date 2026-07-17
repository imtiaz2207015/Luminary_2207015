<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Capsule;

class ProfileController extends Controller
{
    /**
     * Display the specified user's profile.
     */
    public function show(User $user): View
    {
        $viewer = auth()->user();

        $query = Capsule::where('user_id', $user->id)
            ->where('is_locked', false)
            ->where('status', 'approved')
            ->with('reactions')
            ->latest();

        if ($viewer && $viewer->id === $user->id) {
            // Own profile: show public + friends capsules
            $query->whereIn('visibility', ['public', 'friends']);
        } elseif ($viewer && $viewer->isFriendsWith($user)) {
            // Confirmed friend: show public + friends capsules
            $query->whereIn('visibility', ['public', 'friends']);
        } else {
            // Stranger or guest: only public
            $query->where('visibility', 'public');
        }

        $capsules = $query->get();

        return view('profile.show', compact('user', 'capsules'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($request->hasFile('avatar')) {
            // Delete old avatar from storage before saving the new one
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Clean up avatar file on account deletion
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}