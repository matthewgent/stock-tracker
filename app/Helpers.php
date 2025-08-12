<?php

if ( !function_exists('member') )
{
    function member(): ?\App\Models\Member {
        return auth()->user();
    }
}
