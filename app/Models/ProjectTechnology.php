<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTechnology extends Model
{
    use HasFactory;
    protected $table = 'project_technologies';

    protected $fillable = [
        'project_id', 'ProgrammingLanguage_id', 'framework_id', 'platform_id', 'ExtraXP'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function programmingLanguage()
    {
        return $this->belongsTo(ProgrammingLanguage::class,'ProgrammingLanguage_id');
    }

    public function framework()
    {
        return $this->belongsTo(Framework::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
