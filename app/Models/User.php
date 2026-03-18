<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\userRoles;
use App\Traits\TimeRangeScope;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable{
	/** @use HasFactory<\Database\Factories\UserFactory> */
	use HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'user_roles_id',
		'last_login_at',
		'is_active',
		'phone_number',
	];

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
	protected function casts(): array{
		return [
			'email_verified_at' => 'datetime',
			'last_login_at' => 'datetime',
			'password' => 'hashed',
		];
	}

	// relationships
	public function myRoles(): BelongsTo {
		return $this->belongsTo(userRoles::class, 'user_roles_id');
	}
}
