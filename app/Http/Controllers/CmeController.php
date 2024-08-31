<?php

namespace App\Http\Controllers;

use App\Services\CmeService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Cme\CmeResource;
use App\Http\Resources\Cme\CmeCollection;
use App\Http\Requests\CmeAnswerStoreRequest;

class CmeController extends Controller
{
    private $cmeService;

    /**
     * @return void
    */
    
    public function __construct(){
       $this->cmeService = new CmeService();
    }

    /**
     * Display a listing of the resource.
     *
    */
    public function index(Request $request)
    {
        $cmeList = $this->cmeService->getAll($request);
        return ApiResponse::successResponse('CME List has been fetched successfully.', new CmeCollection($cmeList));
    }

    /**
     * store CME member answer.
     *
    */
    public function store(CmeAnswerStoreRequest $request)
    {
        $cmeAnswer = $this->cmeService->store($request);
        return ApiResponse::successResponse('CME answer has been stored successfully.', new CmeResource($cmeAnswer));
    }
    
    /**
     * Get a details of the resource with request data.
     *
    */
    public function showDetails(Request $request)
    {
        $cmeDetails = $this->cmeService->getDetails($request);
        return ApiResponse::successResponse('CME details has been fetched successfully.', $cmeDetails ? new CmeResource($cmeDetails) : []);
    }
}
