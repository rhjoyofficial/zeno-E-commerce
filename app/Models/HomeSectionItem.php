<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_section_id',
        'category_id',
        'title',
        'subtitle',
        'image',
        'order',
        'status',
        'created_by',
        'updated_by'
    ];

    public function section()
    {
        return $this->belongsTo(HomeSection::class, 'home_section_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
