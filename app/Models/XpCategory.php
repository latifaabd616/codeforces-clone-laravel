<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class XpCategory extends Model
{
    use HasFactory, HasTranslations;
    
    protected $table = 'xpcategories'; // حدد اسم الجدول هنا

      protected $fillable = [
        'Title', 
        'Icon',
        'title_translations'
        // يمكنك إضافة حقول أخرى هنا إذا لزم الأمر
    ];
    // تحديد الحقول القابلة للترجمة
    public $translatable = ['title_translations'];
      protected $casts = [
        'title_translations' => 'array',
    ];
      protected $appends = [
        'xpcategory_url',
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

    public function user_projects()
    {
        return $this->belongsToMany(User_project::class,'user_project_xpcategories',  'Xpcategory_id', 'UserProject_id'  )            
        ->withPivot('XPValue', 'Notice');
                    
    }
  

  public function getxpcategoryUrlAttribute()
    {
        return (asset('storage') . '/xpcategory/' . $this->attributes['Icon'])??null;
    }
}
