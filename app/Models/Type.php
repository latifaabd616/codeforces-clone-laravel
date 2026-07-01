<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Type extends Model
{
    use HasFactory,HasTranslations;
     protected $fillable = ['Title', 'Criteria', 'Icon', 'GrantDate','title_translations',
        'criteria_translations'];
     protected $dates = ['GrantDate']; 
         
    // تحديد الحقول القابلة للترجمة
    public $translatable = ['title_translations', 'criteria_translations'];
    
    protected $casts = [
        'title_translations' => 'array',
        'criteria_translations' => 'array',
    ];
     protected $appends = [
        'type_url',
        'title', // إضافة accessor للحقل المترجم
        'criteria', // إضافة accessor للحقل المترجم
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

    // Accessor for criteria with translation
    protected function criteria(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $locale = app()->getLocale();
                $translations = $attributes['criteria_translations'] ?? [];
                
                if (is_string($translations)) {
                    $translations = json_decode($translations, true) ?? [];
                }
                
                return $translations[$locale] ?? $attributes['Criteria'] ?? null;
            }
        );
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
  

  public function getTypeUrlAttribute()
    {
        return (asset('storage') . '/type/' . $this->attributes['Icon'])??null;
    }
}
