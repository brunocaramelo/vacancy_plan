<?php

namespace App\Services;

use App\Interfaces\HolidayPlanInterface;

use App\Exceptions\HolidayNotFoundException;

class HolidayPlanService
{
    private $holidayPlanRepository;

    public function __construct(HolidayPlanInterface $strategyWms)
    {
        $this->holidayPlanRepository = $strategyWms;
    }

    public function searchAndPaginate(array $data)
    {
        return $this->holidayPlanRepository->searchAndPaginate($data);
    }
    public function getVerboseById(string $id)
    {

        $instance = $this->holidayPlanRepository->getVerboseById($id);

        if($instance == null) {
            throw new HolidayNotFoundException('Holiday not found');
        }

        return $instance;
    }

    public function store(array $params) :array
    {
        return \DB::transaction(function() use ($params) {

            $response = $this->holidayPlanRepository->create($params);

            return [
                'status' => 'success',
                'message' => 'Holiday Plan created',
                'data' => $response->toArray(),
            ];

        });
    }
    public function update(string $id, array $params) :array
    {
        return \DB::transaction(function() use ($id, $params) {

            $response = $this->holidayPlanRepository->update($id, $params);

            return [
                'status' => 'success',
                'message' => 'Holiday Plan updated',
                'data' => $response->toArray(),
            ];

        });
    }

}
