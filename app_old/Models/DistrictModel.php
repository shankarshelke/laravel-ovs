<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    protected $table = "districts";
    protected $fillable = [
    					    'id',
    					    'district_name',
    					    'fk_st_id'
    					  ];
}
?>