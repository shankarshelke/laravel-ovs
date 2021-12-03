<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulesModel extends Model
{
    protected $table    = 'modules';
    protected $fillable = [
    'module_name',
    'module_slug',
    'is_view',
    'is_create',
    'is_edit',
    'is_delete',
    'is_approved',
    'permissions'
    ];
}
