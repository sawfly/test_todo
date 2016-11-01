<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $fillable = ['name', 'category_id'];

    protected $dates = ['deleted_at'];

    /**
     * One-to-many relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->belongsTo('App\Category');
    }
}
