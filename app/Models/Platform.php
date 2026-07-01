<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Platform extends Model
{
    use HasFactory, HasTranslations;
     protected $table = 'platforms';
    
    protected $fillable = [
        'Title', 
        'Icon',
         'title_translations'
    ];
        // تحديد الحقول القابلة للترجمة
    public $translatable = ['title_translations'];
    
    protected $casts = [
        'title_translations' => 'array',
    ];
    
    protected $appends = [
        'platform_url',
        'title' // إضافة accessor للحقل المترجم
    ];
     // Accessor for title with translation
    protected function title(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $locale = app()->getLocale();
                $translations = $attributes['title_translations'] ?? [];
                
                if (is_string($translations)) {
                    $translations = json_decode($translations, true) ?? [];
                }
                
                return $translations[$locale] ?? $attributes['Title'] ?? null;
            }
        );
    }

    public function users()
{
    return $this->belongsToMany(User::class);
               
}
public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
           public function userprojects()
{
    return $this->belongsToMany(User_project::class);
                
}
public function userPreferences()
{
    return $this->hasMany(UserPreference::class, 'Platform_id');
}


  public function getPlatformUrlAttribute()
    {
        return (asset('storage') . '/platform/' . $this->attributes['Icon'])??null;
    }

}

