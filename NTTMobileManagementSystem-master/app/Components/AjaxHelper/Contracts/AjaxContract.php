<?php

namespace App\Components\AjaxHelper\Contracts;

use Illuminate\Http\Request;

interface AjaxContract {
    function ajaxHandler(Request $request);
}
