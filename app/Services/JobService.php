<?php

namespace App\Services;


use App\Models\Job;

class JobService
{
    public function deleteJob($id) {
        //Find the job with id x or fail and throw and exception
        $job = Job::findOrFail($id);

        //Delete physically from DB
        $job->delete();
    }
}