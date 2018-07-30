<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'author_id', 'image_id'
    ];

    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    public function author()
    {
        return $this->belongsTo('App\User');
    }
}
