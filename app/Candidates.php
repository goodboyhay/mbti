<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Candidates extends Model
{
   use Sortable;
   protected $fillable=[
      'name', 
      'email', 
      'dob',
      'position',
      'token_key'
   ];
   
   protected $primaryKey = ' candidate_id';
   public $sortable = ['candidate_id','name','updated_at','position'];
}
