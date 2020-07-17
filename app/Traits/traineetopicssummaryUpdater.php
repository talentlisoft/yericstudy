<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * 学员问题汇总表更新
 */
trait traineetopicssummaryUpdater
{
    /**
     * @param int $trainee_id
     * @param int $topic_id
     * @param bollean $result
     */
    public function updatetraineetopicsummary($trainee_id, $topic_id, $result)
    {
        if (DB::table('trainee_topics_summary')->where('trainee_id', $trainee_id)->where('topic_id', $topic_id)->exists()) {
            DB::table('trainee_topics_summary')
                ->where('trainee_id', $trainee_id)
                ->where('topic_id', $topic_id)
                ->increment($result?'correct_count':'fail_count', 1, [
                    'recent_failed' => ($result==false) ? true : false,
                    'updated_at' => Carbon::now()
                ]);
        } else {
            DB::table('trainee_topics_summary')->insert([
                'trainee_id' => $trainee_id,
                'topic_id' => $topic_id,
                'correct_count' => ($result? 1 : 0),
                'fail_count' => ($result==false ? 1 : 0),
                'recent_failed' => ($result==false) ? true : false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
