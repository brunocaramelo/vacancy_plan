<?php

namespace App\Repositories;

use App\Models\Holiday;
use App\Interfaces\HolidayPlanInterface;

use App\Models\Participant;
class HolidayPlanRepository implements HolidayPlanInterface
{
    private $model = Holiday::class;

    public function searchAndPaginate(array $filters)
    {
        return $this->filterBuildData($filters)
                    ->paginate($filters['page_size'] ?? 10);
    }

    public function getVerboseById(string $id)
    {
        return $this->filterBuildData(['id' => $id])
                    ->first();
    }
    public function delete(string $id)
    {
        return $this->filterBuildData(['id' => $id])
                    ->first()
                    ->delete();
    }

    public function filterBuildData(array $filters)
    {
        return $this->model::with(['participants' => function($query) use ($filters) {
                $query->when(!empty($filters['participant_email']), function ($query) use ($filters) {
                    $query->where('email', 'ILIKE' , '%'.$filters['participant_email'].'%');
                })->when(!empty($filters['participant_name']), function ($query) use ($filters) {
                    $query->where('name', 'ILIKE' , '%'.$filters['participant_name'].'%');
                });
            }])
            ->when(!empty($filters['title']), function ($query) use ($filters) {
                $query->where('title', 'ILIKE' , '%'.$filters['title'].'%');
            })
            ->when(!empty($filters['date_start']) && !empty($filters['date_end']), function ($query) use ($filters) {
                $query->whereBetween('date', [$filters['date_start'], $filters['date_end']]);
            })
            ->when(!empty($filters['date_start']) && empty($filters['date_end']), function ($query) use ($filters) {
                $query->where('date', '<',$filters['date_start']);
            })
            ->when(empty($filters['date_start']) && !empty($filters['date_end']), function ($query) use ($filters) {
                $query->where('date', '>',$filters['date_start']);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', '=',$filters['id']);
            });
    }

    public function create(array $data)
    {
        $instance = $this->model::create($data);

        $this->syncRelationParticipants($instance, $data['participants'] ?? []);

        return $instance;
    }

    public function update($id, array $data)
    {
        $instance = $this->model::find($id);

        $instance->update($data);

        $this->syncRelationParticipants($instance, $data['participants'] ?? []);

        return $instance;
    }

    public function syncRelationParticipants($holidayInstance, $params): void
    {
        $participantList = [];

        foreach($params as $participant) {
            $partItemId = Participant::where('email', $participant['email'])->first()->id ?? null;

            if($partItemId == null) {
                $partItemId = Participant::create($participant)->id;
            }

            $participantList[] = $partItemId;
        }

        if(count($participantList) > 0) {
            $holidayInstance->participants()->sync($participantList);
        }
    }

}
