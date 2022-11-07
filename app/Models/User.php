<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //un usuario puede hacer muchos posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    //un usuario puede hacer muchos likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    /*
        relacion inversa
        los usuarios que me siguen
        muchos usuarios -> siguen a este usuario
    */
    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }

    //comprobar si un usuario ya me sigue
    /*
        relacion a partir de una relacion
        existe <- usuario siguiendo <- usuario
    */
    public function siguiendo(User $user)
    {
        return $this->followers->contains($user->id);
    }

    //almancena los que seguimos
    /*
        relacion inversa
        Los usuarios que yo sigo
        siguiendo <- usuario
    */
    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }
}