<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait InteractsWithRoles
{
    protected function requireRole(Request $request, array $roles): void
    {
        abort_unless(in_array($request->user()->role, $roles, true), 403);
    }
}
