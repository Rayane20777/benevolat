<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        "status",
        "user_id",
        "annonce_id",
        
    ]; 

    public function users():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function annonces():BelongsTo
    {
        return $this->belongsTo(Annonce::class);
    }
}
