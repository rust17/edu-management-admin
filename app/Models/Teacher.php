<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Teacher Model
 * Stores teacher information such as title, education. This table can be associated when resources
 * frequently need specific teacher fields.
 */
class Teacher extends Model
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