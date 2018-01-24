<?php

namespace App\Repositories\Contracts;

use App\Models\Contracts\ModelInterface;
use Illuminate\Http\Request;

interface RepositoryInterface
{

    public function insertData(Request $request);

    public function updateData(Request $request, string $code);
}