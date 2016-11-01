<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $fillable = ['name'];

    public $timestamps = false;

    protected $dates = ['created_at'];

    /**
     * One-to-many relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    /**
     * @param $data
     * @return $this|static
     */
    public function add($data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable))
                $fields[$key] = $value;
        }
        if (!empty($fields))
            return self::create($fields);
        else return $this;
    }

    /**
     * collection with counted related tasks
     * @return mixed
     */
    public static function withTasks(){
        return DB::table('categories')->leftJoin(DB::raw('(SELECT category_id, count(category_id) as tasks FROM tasks 
        WHERE deleted_at is null GROUP BY category_id) AS c'), 'categories.id', '=', 'category_id')->get(['id', 'name',
            'tasks']);
    }
}
