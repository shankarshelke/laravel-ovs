<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsLetterTemplateModel extends Model
{
    use SoftDeletes;
    protected $table = "newsletter_template";
   	
   	protected $fillable = [
   							'id',	
   							'title',
   							'subject',
   							'news_description',
   							'status'
   						  ]; 
}
