<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Notification extends Model
{
    use HasFactory, HasTranslations;
    protected $fillable = [
        'user_id',
        'Title', 
        'Description',
        'ReceiveDate',
        'Is_read',
        'title_translations',
        'description_translations'
    ];
      // تحديد الحقول القابلة للترجمة
    public $translatable = ['title_translations', 'description_translations'];
    
    protected $casts = [
        'ReceiveDate' => 'date',
        'Is_read' => 'boolean',
        'title_translations' => 'array',
        'description_translations' => 'array',
    ];
      protected $appends = [
        'title',
        'description'
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

    // Accessor for description with translation
    protected function description(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $locale = app()->getLocale();
                $translations = $attributes['description_translations'] ?? [];
                
                if (is_string($translations)) {
                    $translations = json_decode($translations, true) ?? [];
                }
                
                return $translations[$locale] ?? $attributes['Description'] ?? null;
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
