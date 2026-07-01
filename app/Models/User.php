<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes; // هذا هو المسار الصحيح
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Level; 
use App\Models\Type;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
        
    protected $fillable = [ 'name', 'email', 'password', 'level_id', 'type_id', 
        'Avatar', 'RegistrationDate', 'Is_Admin', 'TotalXP', 'rank'];
        
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'Is_Admin' => 'boolean',
        'Avatar' => 'string',
    ];
      protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $attributes = [
    'type_id' => 1, // ID نوع normal
    'level_id'=>1,
    'Is_Admin' => false,
    'TotalXP' => 0,
];
 protected $appends = [
        'avatar_url',
 ];
//  public function setPasswordAttribute($value)
// {
//     $this->attributes['password'] = Hash::make($value);
// }



  public function getAvatarUrlAttribute()
    {
        return (asset('storage') . '/avatars/' . $this->attributes['Avatar'])??null;
    }
     public function level()
     {
        return $this->belongsTo(Level::class,'level_id');
    }
   

        protected static function boot()
        {
        parent::boot();

        // تعيين مستوى "مبتدأ" تلقائيًا عند إنشاء المستخدم
        static::creating(function ($user) {
            if (empty($user->level_id)) {
                $beginnerLevel = \App\Models\Level::where('Title', 'Beginner')->first();
                $user->level_id = $beginnerLevel?->id; // استخدم الناقل الآمن (?.) لتجنب الأخطاء
            }
        });
          
      
    }
    public function type()
     {
        return $this->belongsTo(Type::class);
    }

    public function Leader_boards()
    {
        return $this->hasMany(Leader_board::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function platforms()
    {
        return $this->belongsToMany(Platform::class);
                   
    }
    public function badges()
{
    return $this->belongsToMany(Badge::class,'user_badges','User_id','Badge_id')
                                  ->withPivot('AwardDate')
                                  ->withTimestamps();
                
}
/////////////////////////
public function projects ()
{
    return $this->belongsToMany(Project ::class,'user_projects','user_id', 'Project_id')
       
        ->withPivot(['favorite','StartDate', 'FinishDate', 'Status', 'ReviewStatus', 'ReviewDate', 'Submittedfile']);
                
}
public function acceptedProjects()
{
    return $this->belongsToMany(Project::class, 'user_projects')
               //->wherePivot('Submittedfile', 1) // أو true حسب كيف يتم تخزينه
                ->wherePivot('ReviewStatus', 'accepted')
                ->withPivot(['favorite','StartDate', 'FinishDate', 'ReviewDate', 'ReviewStatus','Status','Submittedfile']);
}

     
  public function badgesCount()
    {
        return $this->badges()->count();
    }
    ////////////////////////////////////
 /*      public function userProjects()
{
    return $this->hasMany(UserProject::class);
} */
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
  
}
