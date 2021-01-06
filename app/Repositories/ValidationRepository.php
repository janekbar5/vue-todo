<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Validator;

class ValidationRepository
{

    public function todoCreate()
     {        
         $rules = array(           
            'title' => 'required',					
         );        
         return $rules;
     }
    public function todoUpdate()
     {        
         $rules = array(           
            'title' => 'required',					
         );        
         return $rules;
     }
   

       
}
