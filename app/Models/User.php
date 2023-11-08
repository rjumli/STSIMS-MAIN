<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use \Spatie\WelcomeNotification\ReceivesWelcomeNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Jobs\MailUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuthenticationLoggable, ReceivesWelcomeNotification;

    protected $fillable = [
        'username',
        'email',
        'password',
        'temp_password',
        'role',
        'is_active',
        'avatar',
        'welcome_valid_until'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\UserProfile', 'user_id');
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
    
    public function scopeVerify(){
        $mail = MailUser::dispatch($this->id)->delay(now()->addSeconds(10));
        return $mail;
    }
}
