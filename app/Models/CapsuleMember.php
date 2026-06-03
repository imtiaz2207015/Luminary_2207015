<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapsuleMember extends Model {
    protected $fillable = ['capsule_id', 'user_id', 'role', 'status'];
    public function capsule() { return $this->belongsTo(Capsule::class); }
    public function user() { return $this->belongsTo(User::class); }
}