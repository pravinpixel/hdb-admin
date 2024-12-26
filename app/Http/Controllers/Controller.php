<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public const REQUESTED = 1;
    public const REQUEST_ACCEPTED = 2;
    public const ITEM_ISSUED = 1;
    public const REQUEST_REJECTED = 3;
    public const ISSUE_ITEM = 0;
    public const ISSUE_ITEM_CHECKOUT = 1;
    public const RETURN_ITEM = 0;
    public const CANCEL_REQUEST = 0;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

