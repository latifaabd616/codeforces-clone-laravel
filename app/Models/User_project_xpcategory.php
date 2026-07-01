<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_project_xpcategory extends Model
{
    use HasFactory;
    protected $table = 'user_project_xpcategories';

    protected $fillable = [
        'UserProject_id',
        'Xpcategory_id',
        'XPValue',
        'Notice'
    ];
}
