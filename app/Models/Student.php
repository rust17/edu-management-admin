<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Student Model
 * Stores student information such as grade, major. This table can be associated when resources
 * frequently need specific student fields.
 */
class Student extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id'];

    protected $dates = ['deleted_at'];

    /**
     * Get associated user information
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}