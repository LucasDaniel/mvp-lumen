<?php

namespace App\Http\Controllers\Contracts;

use Illuminate\Http\Request;
use App\Repositories\Contracts\RepositoryInterface;

interface ControllerInterface
{
    public function insert(Request $request);

    public function update(Request $request, string $code);

    public function list(Request $request, int $limit, int $offset);
}