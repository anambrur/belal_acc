<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'owner_name',
        'phone',
        'email',
        'address',
        'city',
        'logo',
        'invoice_footer',
    ];

    /**
     * Singleton accessor for the active store profile.
     */
    public static function current(): ?self
    {
        return static::query()->first();
    }

    /**
     * Public URL for the store logo (uploaded or default asset).
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }

        return asset('assets/img/anamsLogo.png');
    }

    /**
     * Absolute filesystem path for DomPDF (must not be a remote URL).
     */
    public function getLogoPathAttribute(): string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return Storage::disk('public')->path($this->logo);
        }

        return public_path('assets/img/anamsLogo.png');
    }

    /**
     * Full address line including optional city.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->address, $this->city]);

        return implode(', ', $parts);
    }
}
