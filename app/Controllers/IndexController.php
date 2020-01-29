<?php
namespace App\Controllers;
use App\Models\{Job, Project};

class IndexController extends BaseController
{
    public function indexAction()
    {
        //Get all jobs from DB
        $jobs = Job::all();
        //Assign name for portfolio
        $name = 'Axel Espinosa';
        //Filter jobs by months
        $limitMonths = 0;
        $jobFilter = function (array $job) use ($limitMonths) {
            return $job['months'] >= $limitMonths;
        };

        $jobs = array_filter($jobs->toArray(), $jobFilter);
        //Call the views
        return $this->renderHTML('index.twig', [
            'name' => $name,
            'jobs' => $jobs
        ]);
    }

}