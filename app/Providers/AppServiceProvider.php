<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider; // هذا هو الاستيراد الصحيح
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
          Validator::extend('alpha_spaces_underscore', function ($attribute, $value) {
        return preg_match('/^[\p{Arabic}\p{L}\s_]+$/u', $value);
          // قاعدة التحقق للإيميل
   Validator::extend('strict_email', function ($attribute, $value) {
        return preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value);
    });
        
    });
   
    // رسالة الخطأ
    Validator::replacer('alpha_spaces_underscore_at', function ($message, $attribute, $rule, $parameters) {
        return str_replace(':attribute', $attribute, 'The :attribute may only contain letters, spaces, and dashes.');
    });
    
      Validator::replacer('strict_email', function ($message, $attribute, $rule, $parameters) {
        return 'يجب أن يحتوي الإيميل على تنسيق صحيح (مثال: name@example.com) ولا يحتوي على أحرف خاصة';
    });
   User::observe(\App\Observers\UserObserver::class);
    }
}
