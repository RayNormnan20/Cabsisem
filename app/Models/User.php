<?php

namespace App\Models;

use App\Notifications\UserCreatedNotification;
use Devaslanphp\FilamentAvatar\Core\HasAvatarUrl;
use DutchCodingCompany\FilamentSocialite\Models\SocialiteUser;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JeffGreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use ProtoneMedia\LaravelVerifyNewEmail\MustVerifyNewEmail;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable,
        HasRoles, HasAvatarUrl, SoftDeletes, MustVerifyNewEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'creation_token',
        'type',
        'oidc_username',
        'oidc_sub',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'remember_token',
        'is_vendedor',
        'vendedor_id',
        'fecha_ingreso',
        'fecha_egreso',
        'comision',
        'perfil',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'is_vendedor' => 'boolean',
        'fecha_ingreso' => 'datetime',
        'fecha_egreso' => 'datetime',
        'comision' => 'decimal:2',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (User $item) {
            if ($item->type == 'db') {
                $item->password = bcrypt(uniqid());
                $item->creation_token = Uuid::uuid4()->toString();
            }
        });

        static::created(function (User $item) {
            if ($item->type == 'db') {
                $item->notify(new UserCreatedNotification($item));
            }
        });
    }

    /**
     * Relación con el modelo Vendedor
     */
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    /**
     * Accesor para verificar si es vendedor
     */
    public function isVendedor(): Attribute
    {
        return new Attribute(
            get: fn () => $this->is_vendedor || $this->vendedor_id !== null
        );
    }

    /**
     * Scope para filtrar usuarios vendedores
     */
    public function scopeVendedores($query)
    {
        return $query->where('is_vendedor', true)
                    ->orWhereNotNull('vendedor_id');
    }

    // Relaciones existentes
    public function projectsOwning(): HasMany
    {
        return $this->hasMany(Project::class, 'owner_id', 'id');
    }

    public function projectsAffected(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id')->withPivot(['role']);
    }

    public function favoriteProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_favorites', 'user_id', 'project_id');
    }

    public function ticketsOwned(): HasMany
    {
        return $this->hasMany(Ticket::class, 'owner_id', 'id');
    }

    public function ticketsResponsible(): HasMany
    {
        return $this->hasMany(Ticket::class, 'responsible_id', 'id');
    }

    public function socials(): HasMany
    {
        return $this->hasMany(SocialiteUser::class, 'user_id', 'id');
    }

    public function hours(): HasMany
    {
        return $this->hasMany(TicketHour::class, 'user_id', 'id');
    }

    public function totalLoggedInHours(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->hours->sum('value');
            }
        );
    }

    public function canAccessFilament(): bool
    {
        return true;
    }
}