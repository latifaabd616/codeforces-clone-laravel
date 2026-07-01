<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'projects';
  protected $fillable = [
        'Title', 
        'ShortDescription', 
        'LongDescription', 
        'TimeLimit', 
        'Difficulty', 
        'XPReward',
        'title_translations',
        'short_description_translations',
        'long_description_translations',
        'difficulty_translations'
    ];
    // تحديد الحقول القابلة للترجمة
    public $translatable = [
        'title_translations',
        'short_description_translations',
        'long_description_translations',
        'difficulty_translations'
    ];
    
    protected $casts = [
        'title_translations' => 'array',
        'short_description_translations' => 'array',
        'long_description_translations' => 'array',
        'difficulty_translations' => 'array',
    ];
    
    protected $appends = [
        'title',
        'short_description',
        'long_description',
        'difficulty'
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

    // Accessor for short description with translation
    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $locale = app()->getLocale();
                $translations = $attributes['short_description_translations'] ?? [];
                
                if (is_string($translations)) {
                    $translations = json_decode($translations, true) ?? [];
                }
                
                return $translations[$locale] ?? $attributes['ShortDescription'] ?? null;
            }
        );
    }

    // Accessor for long description with translation
    protected function longDescription(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $locale = app()->getLocale();
                $translations = $attributes['long_description_translations'] ?? [];
                
                if (is_string($translations)) {
                    $translations = json_decode($translations, true) ?? [];
                }
                
                return $translations[$locale] ?? $attributes['LongDescription'] ?? null;
            }
        );
    }

    // Accessor for difficulty with translation
    protected function difficulty(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $locale = app()->getLocale();
                $translations = $attributes['difficulty_translations'] ?? [];
                
                if (is_string($translations)) {
                    $translations = json_decode($translations, true) ?? [];
                }
                
                return $translations[$locale] ?? $attributes['Difficulty'] ?? null;
            }
        );
    }
     public function frameworks()
    {
        return $this->belongsToMany(Framework::class,'project_technologies', 'project_id', 'framework_id')
        ->withPivot('ExtraXP')->wherePivotNotNull('framework_id');
        
                    
    }
    public function platforms()
    {
       return $this->belongsToMany(Platform::class,'project_technologies', 'project_id', 'platform_id')
       ->withPivot('ExtraXP' )->wherePivotNotNull('platform_id');
   }

   public function programmingLanguages()
{
    return $this->belongsToMany(ProgrammingLanguage::class,'project_technologies', 'project_id', 'ProgrammingLanguage_id')
          ->withPivot('ExtraXP')->wherePivotNotNull('ProgrammingLanguage_id');
          
}

public function projects ()
{
    return $this->belongsToMany(Project ::class);
                
} 
public function users ()
{
    return $this->belongsToMany(User ::class,'user_projects','user_id', 'Project_id')
    ->using(UserProject::class)
        ->withPivot(['favorite','StartDate', 'FinishDate', 'Status', 'ReviewStatus', 'ReviewDate', 'Submittedfile']);
                
} 
 // العلاقة مع جدول التقنيات مباشرة
 public function technologies()
    {
        return $this->hasMany(ProjectTechnology::class);
    }

}
