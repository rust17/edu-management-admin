<?php

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Create payment records for all paid invoices
        Invoice::where('status', Invoice::STATUS_PAID)->get()->each(function ($invoice) {
            factory(Payment::class)->create([
                'invoice_id' => $invoice->id,
                'student_id' => $invoice->student_id,
                'amount' => $invoice->amount,
                'paid_at' => now(),
                'transaction_no' => Str::random(30),
                'transaction_fee' => 10,
                'payment_platform' => 'omise',
                'payment_method' => 'card',
            ]);
        });
    }
}
