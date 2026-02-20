<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedReport extends Model
{
    protected $fillable = [
        'img_path',
        'file_type',
        'hash_bytecode_header',
        'hash_bytecode_full',
        'hash_text_content',
        'hash_visual_content',
        'pdf_type',
        'page_count',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'page_count' => 'integer',
    ];
}