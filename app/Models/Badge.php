<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Badge extends Model
{
    use HasFactory,HasTranslations;
    
   protected $fillable = ['Title', 'Criteria', 'Icon','title_translations', 'criteria_translations'];
     // تحديد الحقول القابلة للترجمة
    public $translatable = ['title_translations', 'criteria_translations'];
    protected $casts = [
        'title_translations' => 'array',
        'criteria_translations' => 'array',
    ];
      
        protected $appends = [
        'icon_url',
        'title', // إضافة accessor للحقل المترجم
        'criteria', // إضافة accessor للحقل المترجم
     ];

 // Accessor for title with translation
    protected function title(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                  // 1. الحصول على اللغة الحالية
                $locale = app()->getLocale();
                // 2. جلب الترجمات من قاعدة البيانات
                $translations = $attributes['title_translations'] ?? [];
                 // 3. إذا كانت الترجمات مخزنة كـ string (JSON) نحولها إلى array

                if (is_string($translations)) {
                    $translations = json_decode($translations, true) ?? [];
                }
                 // 4. إرجاع الترجمة المناسبة أو القيمة الافتراضية
                return $translations[$locale] ?? $attributes['Title'] ?? null;
            }
        );
    }

    // Accessor for criteria with translation
    protected function criteria(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // 1. الحصول على اللغة الحالية
                $locale = app()->getLocale();
                  // 2. جلب الترجمات من قاعدة البيانات
                $translations = $attributes['criteria_translations'] ?? [];
                   // 3. إذا كانت الترجمات مخزنة كـ string (JSON) نحولها إلى array
                if (is_string($translations)) {
                    $translations = json_decode($translations, true) ?? [];
                }
                // 4. إرجاع الترجمة المناسبة أو القيمة الافتراضية
                return $translations[$locale] ?? $attributes['Criteria'] ?? null;
            }
        );
    }

 
   
    public function users()
    {
        return $this->belongsToMany(User::class,'user_badges','Badge_id','User_id')
                                          ->withPivot('AwardDate')
                                          ->withTimestamps();
                    
    }
  

  public function getIconUrlAttribute()
    {
        return (asset('storage') . '/badges/' . $this->attributes['Icon'])??null;
    }
}
