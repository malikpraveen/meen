<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $status = false;
    protected $errors = [];
    protected $data = [];
    protected $error = '';
    protected $message = '';
    protected $status_code = 200;
    protected $type = 'get';

    protected function populateResponse($used_validator = true)
    {

        if($this->status == false){
            if ($used_validator) {
                $this->error = $this->message->first();
                $this->errors = $this->message;
            }
            else {
                $this->error = $this->message;
            }
            $this->message = $this->message->first();
            $this->status_code = 202;
            
        }

        if(empty($this->data)){
            if($this->type == 'get'){
                $this->data = new \stdClass();
            }
            
        }

        return response()->json([
            'status'        => $this->status,
            'status_code'   => $this->status_code,
            'data'          => $this->data,
            'error'         => $this->error,
            'errors'        => $this->errors,
            'message'       => $this->message
        ]);
    }
}
