<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function parentcategory() {
        return $this->hasOne(Category :: class, 'id', 'parent_id')
        ->select('id', 'name', 'url')
        ->where('status', 1)
        ->orderBy('id', 'ASC');
        }

}
