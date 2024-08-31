<?php

namespace App\Repositories;

use App\Models\Cme;
use App\Models\CmeHistory;
use App\Models\CmeHistoryDetail;
use App\Constants\Constants;

class CmeRepository
{
    protected $model;

    public function __construct()
    {
        $this->model                = new Cme();
        $this->historyModel         = new CmeHistory();
        $this->historyDetailModel   = new CmeHistoryDetail();
    }

    public function findByMultipleFields($where_clause, $multiple = false, $request=null)
    {
        $cme = $this->model->where($where_clause);
        if($request->has('map_type')){
            $cme = $cme->whereHas('cmeMap', function($query) use($request) {
                $query->where('map_type', $request->map_type);
                if($request->has('map_type_id')){
                    $query->where('map_type_id', $request->map_type_id);
                }
            });
        }
        if ($multiple) {
            if($request->has('nopagination')){
                return $cme->get();
            }
            return $cme->paginate(Constants::PAGINATION_LENGTH);
        } else {
            return $cme->where($where_clause)->first();
        }
    }

    public function findHistoryByMultipleFields($where_clause, $multiple = false, $request=null)
    {
        $history = $this->historyModel->where($where_clause);
        if ($multiple) {
            if($request->has('nopagination')){
                return $history->get();
            }
            return $history->paginate(Constants::PAGINATION_LENGTH);
        } else {
            return $history->where($where_clause)->first();
        }
    }

    public function createOrUpdateHistory($data)
    {
        $history = $data['cmeHistory'];
        $cmeHistory = $this->historyModel->where([
            'cme_id'    => $history['cme_id'],
            'member_id' => $history['member_id'],
            'type'      => $history['type'],
            'type_id'   => $history['type_id'],
        ])->first();

        if(!$cmeHistory){
            $cmeHistory = $this->historyModel->create($history);
        }else{
            $history['attempt_number'] = $cmeHistory->attempt_number + 1;
            $cmeHistory->update($history);
            $cmeHistory =  $cmeHistory->refresh();
        }
        
        $this->historyDetailModel->where('cme_history_id',$cmeHistory->id)->delete();
        foreach ($data['cmeHistoryDetails'] as $historyDetail) {
            $historyDetail['cme_history_id'] = $cmeHistory->id;
            $this->historyDetailModel->create($historyDetail);
        }
        return $cmeHistory;
    }

}
