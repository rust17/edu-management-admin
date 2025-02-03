<?php

namespace App\Models;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'no', 'course_id', 'student_id', 'status', 'amount', 'sent_at'
    ];

    protected $dates = ['deleted_at', 'sent_at'];

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';

    public static function bootInvoice()
    {
        static::creating(function ($model) {
            $model->no = static::generateNo();
        });
    }

    // Get associated course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Get associated student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Get associated payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Generate invoice number
     *
     * @return string
     */
    public static function generateNo()
    {
        // Use microsecond timestamp as prefix
        $prefix = date('YmdHis') . sprintf('%03d', microtime(true) * 1000 % 1000);
        // Add 6-digit random number
        $suffix = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        return $prefix . $suffix;
    }
}
