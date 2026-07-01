<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Level extends Model
{
    use HasFactory, HasTranslations;
    
    protected $fillable = [
        'Title',
        'RequiredXP',
        'Icon',
        'title_translations'
    ];
   
    // تحديد الحقول القابلة للترجمة
    public $translatable = ['title_translations'];
    
    protected $casts = [
        'title_translations' => 'array',
    ];
     protected $appends = [
        'level_url',
         'title' // إضافة accessor للحقل المترجم
    ];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }


  public function getLevelUrlAttribute()
    {
        return (asset('storage') . '/level/' . $this->attributes['Icon'])??null;
    }

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
}
