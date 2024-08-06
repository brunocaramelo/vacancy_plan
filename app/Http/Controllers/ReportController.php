<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;

use App\Services\HolidayPlanService;

class ReportController extends Controller
{
    private $holidayService;

    public function __construct(HolidayPlanService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    /**
     *
     * @OA\Get(
     *     path="/api/holiday/report/{id}",
     *     tags={"report"},
     *     operationId="generateReportItem",
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
     *     ),
     *     security={
     *        {"bearerAuth": {}},
     *     },
     * )
     */
    public function generateReportById($id)
    {
        $instanceData = $this->holidayService->getVerboseById($id);

        $html = view('pdf.document', [
            'title' => $instanceData->title,
            'description' => $instanceData->description,
            'location' => $instanceData->location,
            'date' => $instanceData->date,
            'participants' => $instanceData->participants,
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return $dompdf->stream('report_holiday.pdf');
    }
}
