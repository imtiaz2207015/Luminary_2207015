<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Capsule;

class ProfileController extends Controller
{
    public function show(User $user)
{
    $capsules = Capsule::where('user_id', $user->id)
        ->where('visibility', 'friends')
        ->where('is_locked', false)
        ->where('status', 'approved')
        ->with('reactions')
        ->latest()
        ->get();

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
    public function update(ProfileUpdateRequest $request): RedirectResponse {
    $request->user()->fill($request->validated());

    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars', 'public');
        $request->user()->avatar = $path;
    }

    if ($request->user()->isDirty('email')) {
        $request->user()->email_verified_at = null;
    }

    $request->user()->save();

    return redirect()->route('profile.edit')->with('success', 'Profile updated!');

}

/**
     * Update the user's font preferences.
     */
    public function updateFont(Request $request): RedirectResponse
    {
        $request->validate([
           'font_style' => ['required', 'string', 'in:default,playfair,dm_sans,serif,georgia,merriweather,lora,inter,poppins,roboto_slab,dancing_script'],
            'font_size'  => ['required', 'string', 'in:xs,sm,medium,lg,xl'],
        ]);

        $request->user()->update([
            'font_style' => $request->font_style,
            'font_size'  => $request->font_size,
        ]);

        return back()->with('success', 'Font preferences updated!');
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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
}


