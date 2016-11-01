<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    public $fillable = ['name', 'category_id'];

    protected $dates = ['deleted_at'];

    private $statuses = [0, 1];

    /**
     * One-to-many relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->belongsTo('App\Category');
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
     * @param $data
     * @return array
     */
    public function updateTask($data)
    {
        $updated = [];
        if (array_key_exists('category_id', $data) && $this->getAttribute('category_id') != $data['category_id']) {
            $this->setAttribute('category_id', $data['category_id']);
            array_push($updated, 'category_id');
        }
        if (array_key_exists('status_id', $data) && in_array($data['status_id'], $this->statuses) &&
            $this->getAttribute('status_id') != $data['status_id']
        ) {
            $this->setAttribute('status_id', $data['status_id']);
            array_push($updated, 'status_id');
        }
        if (array_key_exists('name', $data) && $this->getAttribute('name') != $data['name']) {
            $this->setAttribute('name', $data['name']);
            array_push($updated, 'name');
        }
        if ($updated != [])
            return $this->save() ? $updated : [];
        else return [];
    }

    /**
     * add name of category
     * @return mixed
     */
    public static function allWithCategoryName()
    {
        return DB::table('tasks')->leftJoin('categories', 'categories.id', '=', 'tasks.category_id')
            ->where('tasks.deleted_at')
            ->get(['tasks.id', 'tasks.name', 'categories.name as category', 'tasks.status_id']);
    }
}
