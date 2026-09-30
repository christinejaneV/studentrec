<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'lastname',
    'firstname',
    'province',
    'country',
    'school',
    'program',
    'year',
    'birthday',
    'photo_path',
])]
class Student extends Model
{
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'birthday' => 'date',
        ];
    }
}
