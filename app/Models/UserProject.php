<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;


class UserProject extends  Model
{
    use HasFactory;
    protected $table = 'user_projects';
     protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'project_id',
        'StartDate',
        'FinishDate',
        'Status',
        'ReviewStatus',
        'ReviewDate',
        'Submittedfile',
        'favorite'
    ];
        protected $casts = [
        'StartDate' => 'date',
        'FinishDate' => 'date',
        'ReviewDate' => 'date',
    ];
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'Project_id');
    }
     public function preferences(): HasOne
    {
        return $this->hasMany(UserPreference::class, 'userproject_id');
    }
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
    public function xpCategories()
{
    return $this->belongsToMany(Xpcategory ::class,'user_project_xpcategories','UserProject_id','Xpcategory_id'  )
     ->withPivot('XPValue', 'Notice');
                
}
   public function platforms()
{
    return $this->belongsToMany(Platform::class);
                
}
   public function frameworks()
{
    return $this->belongsToMany(Framework::class);
                
}
   public function programminglanguages()
{
    return $this->belongsToMany(Programming_language::class);
                
}
}
