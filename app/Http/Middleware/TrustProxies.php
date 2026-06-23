<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies = '*'; // Cambia esto según tus necesidades
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
} 