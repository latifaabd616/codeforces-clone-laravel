<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{ 
    use HasFactory;
    protected $fillable = [
        'userproject_id', 'Platform_id', 'framework_id', 'programminglanguage_id'
    ];
     public function userProject()
    {
        return $this->belongsTo(UserProject::class, 'userproject_id');
    }
     public function platform()
    {
        return $this->belongsTo(Platform::class, 'Platform_id');
    }
       public function framework()
    {
        return $this->belongsTo(Framework::class, 'framework_id');
    }
    public function programmingLanguage()
    {
        return $this->belongsTo(ProgrammingLanguage::class, 'programminglanguage_id');
    }
}
