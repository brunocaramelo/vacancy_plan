<?php

namespace App\Http\Controllers;

use App\Services\StrategyWmsService;

use App\Http\Requests\StoreStrategyFormRequest;

use App\Exceptions\PrioriyNotFoundException;

use Illuminate\Http\Request;
class HolidayPlanController extends Controller
{
    private $strategyService;

    public function __construct(StrategyWmsService $strategyService)
    {
        $this->strategyService = $strategyService;
    }

    public function storeHoliday(StoreStrategyFormRequest $request)
    {
        $responseLogin = $this->strategyService->store($request->validated());

        return response()->json( $responseLogin
            , $responseLogin['status'] == 'success' ? 200 : 400);
    }

    /**
    *  @OA\GET(
    *      @OA\PathItem(
    *       path="/api/holidays",
    *       ),
    *      path="/api/holidays",
    *      summary="Get all users",
    *      operationId="holidaySearch"
    *      description="Get all users",
    *      tags={"Test"},
    *      @OA\Parameter(
    *         name="name",
    *         in="query",
    *         description="name",
    *         required=false,
    *      ),
    *     @OA\Parameter(
    *         name="email",
    *         in="query",
    *         description="email",
    *         required=false,
    *      ),
    *     @OA\Parameter(
    *         name="page",
    *         in="query",
    *         description="Page Number",
    *         required=false,
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="OK",
    *          @OA\MediaType(
    *              mediaType="application/json",
    *          )
    *      ),
    *
    *  )
    */
    public function listFiltered(Request $request)
    {
        return $this->strategyService->searchAndPaginate($request->all());

    }


}
