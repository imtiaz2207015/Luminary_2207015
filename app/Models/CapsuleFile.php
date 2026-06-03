<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapsuleFile extends Model {
    protected $fillable = ['capsule_id', 'file_path', 'file_type', 'original_name'];
    public function capsule() { return $this->belongsTo(Capsule::class); }
}