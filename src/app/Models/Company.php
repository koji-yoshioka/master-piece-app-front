<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get rel logo.
     *
     * @return HasOne
     */
    public function logo(): HasOne
    {
        return $this->hasOne('App\Models\CompanyLogo');
    }

    /**
     * Get rel images.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany('App\Models\CompanyImage');
    }

    /**
     * Get rel sellingPoints.
     *
     * @return BelongsToMany
     */
    public function sellingPoints(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\SellingPoint', 'company_selling_point', 'company_id', 'selling_point_id');
    }

    /**
     * Get rel paymentMethods.
     *
     * @return BelongsToMany
     */
    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\PaymentMethod', 'company_payment_method', 'company_id', 'payment_method_id');
    }

    /**
     * Get rel holidays.
     *
     * @return BelongsToMany
     */
    public function holidays(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\DayOfWeek', 'company_holiday', 'company_id', 'day_of_week_id');
    }

    /**
     * Get rel menus.
     *
     * @return HasMany
     */
    public function menus(): HasMany
    {
        return $this->hasMany('App\Models\CompanyMenu');
    }

    /**
     * Get rel reserves.
     *
     * @return HasMany
     */
    public function reserves(): HasMany
    {
        return $this->hasMany('App\Models\CompanyReserve');
    }

    /**
     * Get rel likes.
     *
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany('App\Models\CompanyLike');
    }
}
