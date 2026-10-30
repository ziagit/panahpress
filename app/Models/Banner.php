<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Banner extends Model {
    protected $fillable = ['placement', 'image_path', 'alt', 'link'];
}
