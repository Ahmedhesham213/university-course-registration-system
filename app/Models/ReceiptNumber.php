<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptNumber extends Model {
    protected $fillable = ['receipt_number', 'is_used', 'used_by'];

    public function usedByUser() {
    return $this->belongsTo(User::class, 'used_by');
}
}