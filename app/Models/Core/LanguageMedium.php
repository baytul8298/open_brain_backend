<?php
namespace App\Models\Core;
use Illuminate\Database\Eloquent\Model;
class LanguageMedium extends Model {
    protected $table = 'core.language_medium';
    protected $fillable = ['name','description'];
}
