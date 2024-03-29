<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Annonce extends Model
{

    protected $fillable = [
        "titre",
        "description",
        "date",
        "localisation",
        "type",
        "competence",
        "user_id"
    ]; 




    public function users():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications():HasMany
    {
        return $this->hasMany(Application::class);
    }
}
