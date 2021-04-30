<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StampService;

class StampController extends Controller
{

    private $stampService;

    public function __construct(StampService $stampService)
    {
        $this->stampService = $stampService;
    }


    public function importStamps(Request $request)
    {
        $this->stampService->importStampsFromFile($request);
    }


    public function sendStampsToCourt()
    {
        $this->stampService->prepareStampToSendToCourt();
    }
}
