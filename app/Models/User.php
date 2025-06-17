<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'profile_photo_path',
    ];

    protected $dates = ['deleted_at'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // user heeft meerdere orders
    public function orders(){
        return $this->hasMany(Order::class);
    }

    // reviews heeft meerdere reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // user heeft meerdere wishlists
    public function wishlist(){
        return $this->hasMany(Wishlist::class);
    }

    // USER HEEFT DIT PRODUCT IN DE WISHLIST
    public function hasInWishlist($productId)
    {
        // user heeft dit product in de wishlist
        return $this->wishlist()->where('product_id', $productId)->exists();
    }


    // REVIEW: USER HEEFT DIT PRODUCT GEKOCHT
    public function hasPurchasedProduct($productId)
    {
        return $this->orders()
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->whereIn('payment_status', ['pending', 'paid'])
            ->exists();
    }


    // Alleen user met deze email (admin) kan in de admin panel (backend) inloggen
    public function canAccessPanel(Panel $panel): bool
    {
        // toegang tot admin als user minstens 1 rol heeft
        return $this->roles()->exists();
    }



}
