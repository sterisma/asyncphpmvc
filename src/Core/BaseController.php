<?php

namespace App\Core;


class BaseController 
{ 
    protected function render($view, $data = []) 
    { 
        return ViewRenderer::render($view, $data); 
    } 
    
    protected function json($data) 
    { 
        return JsonResponse::ok($data); 
    } 
}