<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'base_path',
        'width',
        'height',
        'original_filesize',
        'filesize',
        'mime',
        'ext',
    ];

    public const UPDATED_AT = null;
    public const CREATED_AT = 'created_at';
}
