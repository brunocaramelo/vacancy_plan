<?php

namespace App\Http\Controllers;

use App\Services\HolidayPlanService;

use App\Http\Requests\{StoreHolidayFormRequest,
                      UpdateHolidayFormRequest};

use App\Exceptions\HolidayNotFoundException;

use Illuminate\Http\Request;
class HolidayPlanController extends Controller
{
    private $holidayService;
    /**
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     * )
     */
    public function __construct(HolidayPlanService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    /**
     *
     * @OA\Post(
     *     path="/api/holidays",
     *     tags={"holidays"},
     *     operationId="addPet",
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input",
     *     ),
     *       @OA\RequestBody(
     *         required=true,
     *         description="Request Body Description",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Valentines Day Other" ),
     *             @OA\Property(property="date", type="date", example="2024-02-17" ),
     *             @OA\Property(property="description", type="string", example="Valentines Day Description" ),
     *             @OA\Property(property="location", type="string", example="Event Location" ),
     *              @OA\Property(property="participants", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="FistName" ),
     *                     @OA\Property(property="last_name", type="string", example="LastName"),
     *                     @OA\Property(property="email", type="string", example="email@test.com"),
     *                  ),
     *           ),*         ),
     *     ),
     *     security={
     *        {"bearerAuth": {}},
     *     },
     * )
     */
    public function storeHoliday(StoreHolidayFormRequest $request)
    {
        $responseLogin = $this->holidayService->store($request->validated());

        return response()->json( $responseLogin
            , $responseLogin['status'] == 'success' ? 201 : 422);
    }
    /**
     *
     * @OA\Put(
     *     path="/api/holidays/{id}",
     *     tags={"holidays"},
     *     operationId="updateHoliday",
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input",
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id data",
     *         required=true,
     *      ),
     *       @OA\RequestBody(
     *         required=true,
     *         description="Request Body Description",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Valentines Day Other" ),
     *             @OA\Property(property="date", type="date", example="2024-02-17" ),
     *             @OA\Property(property="description", type="string", example="Valentines Day Description" ),
     *             @OA\Property(property="location", type="string", example="Event Location" ),
     *              @OA\Property(property="participants", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="FistName" ),
     *                     @OA\Property(property="last_name", type="string", example="LastName"),
     *                     @OA\Property(property="email", type="string", example="email@test.com"),
     *                  ),
     *           ),*         ),
     *     ),
     *     security={
     *        {"bearerAuth": {}},
     *     },
     * )
     */
    public function updateHoliday($id, UpdateHolidayFormRequest $request)
    {
        $responseLogin = $this->holidayService->update($id, $request->validated());

        return response()->json( $responseLogin
            , $responseLogin['status'] == 'success' ? 200 : 422);
    }
    /**
     *
     * @OA\Delete(
     *     path="/api/holidays/{id}",
     *     tags={"holidays"},
     *     operationId="deleteteHoliday",
     *     @OA\Response(
     *         response=404,
     *         description="Invalid input",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Removed",
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id data",
     *         required=true,
     *      ),
     *     security={
     *        {"bearerAuth": {}},
     *     },
     * )
     */
    public function deleteHoliday($id)
    {
        try {
            $this->holidayService->delete($id);
            return response()->json(['message' => 'Holiday Removed'], 200);
        }   catch (HolidayNotFoundException $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'fail'], 404);
        }
    }

    /**
    *  @OA\Get(
    *      path="/api/holidays",
    *      operationId="holidaySearch",
    *      tags={"holidays"},
    *      @OA\Parameter(
    *         name="participant_email",
    *         in="query",
    *         description="name",
    *         required=false,
    *      ),
    *     @OA\Parameter(
    *         name="participant_name",
    *         in="query",
    *         description="email",
    *         required=false,
    *      ),
    *     @OA\Parameter(
    *         name="title",
    *         in="query",
    *         description="email",
    *         required=false,
    *      ),
    *     @OA\Parameter(
    *         name="date_start",
    *         in="query",
    *         description="2024-10-10",
    *         required=false,
    *      ),
    *     @OA\Parameter(
    *         name="date_end",
    *         in="query",
    *         description="2024-11-10",
    *         required=false,
    *      ),
    *     @OA\Parameter(
    *         name="page",
    *         in="query",
    *         description="Page Number",
    *         required=false,
    *      ),
    *     @OA\Parameter(
    *         name="page_size",
    *         in="query",
    *         description="Page Size",
    *         required=false,
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="OK",
    *          @OA\MediaType(
    *              mediaType="application/json",
    *          )
    *      ),
    *     security={
    *        {"bearerAuth": {}},
    *     },
    *  )
    */
    public function listFiltered(Request $request)
    {
        return $this->holidayService->searchAndPaginate($request->all());

    }

     /**
     *
     * @OA\Get(
     *     path="/api/holidays/{id}",
     *     tags={"holidays"},
     *     operationId="getItem",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id data",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     ),
     *       @OA\Response(
     *         response=200,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Valentines Day Other" ),
     *             @OA\Property(property="date", type="date", example="2024-02-17" ),
     *             @OA\Property(property="description", type="string", example="Valentines Day Description" ),
     *             @OA\Property(property="location", type="string", example="Event Location" ),
     *              @OA\Property(property="participants", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="FistName" ),
     *                     @OA\Property(property="last_name", type="string", example="LastName"),
     *                     @OA\Property(property="email", type="string", example="email@test.com"),
     *                  ),
     *           ),*         ),
     *     ),
     *     security={
     *        {"bearerAuth": {}},
     *     },
     * )
     */
    public function getVerboseById($id)
    {
        try {
            return response()->json($this->holidayService->getVerboseById($id), 200);
        }   catch (HolidayNotFoundException $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'fail'], 404);
        }
    }


}
