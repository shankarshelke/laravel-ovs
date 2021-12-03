<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class NewsletterModel extends Model
{
    protected $table    = 'newsletter';
	protected $fillable = ['email'];
}
