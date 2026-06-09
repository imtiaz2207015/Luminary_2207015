<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Capsule extends Model {
    protected $fillable = [
        'user_id','title','content','unlock_date','is_locked',
        'visibility','status','is_group','group_name','reject_reason','sealed_at'
    ];
    protected $casts = [
        'unlock_date' => 'datetime',
        'is_locked' => 'boolean',
        'is_group' => 'boolean',
        'sealed_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function files() { return $this->hasMany(CapsuleFile::class); }
    public function members() { return $this->hasMany(CapsuleMember::class); }
    public function reactions() { return $this->hasMany(Reaction::class); }
    public function comments() { return $this->hasMany(Comment::class); }

    public function countdown() {
        if (!$this->is_locked) return 'Unlocked';
        $diff = now()->diff($this->unlock_date);
        if ($this->unlock_date->isPast()) return 'Ready to unlock!';
        return "{$diff->days}d {$diff->h}h {$diff->i}m {$diff->s}s";
    }

    public function isUnlockable() {
        return $this->is_locked && $this->unlock_date->isPast();
    }

    public function reactionCount($type) {
        return $this->reactions()->where('type', $type)->count();
    }

    public function userReaction($userId) {
        return $this->reactions()->where('user_id', $userId)->first();
    }
}

