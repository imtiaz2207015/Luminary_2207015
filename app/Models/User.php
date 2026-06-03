<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'avatar', 'bio'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function capsules() { return $this->hasMany(Capsule::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function reactions() { return $this->hasMany(Reaction::class); }
    public function notifications() { return $this->hasMany(Notification::class); }

    public function friendships() {
        return $this->hasMany(Friendship::class);
    }
    public function friends() {
        return $this->hasMany(Friendship::class)->where('status', 'accepted');
    }
    public function isAdmin() { return $this->role === 'admin'; }

    public function getAvatarUrlAttribute() {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1a1a2e&color=f0a500&size=128';
    }
}