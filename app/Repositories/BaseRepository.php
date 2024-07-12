<?php

namespace App\Repositories;

use Ramsey\Uuid\Uuid;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseRepository
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function generateUuid(): string {

        return Uuid::uuid4()->toString();
    }
}
