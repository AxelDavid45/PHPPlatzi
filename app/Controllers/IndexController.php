<?php
namespace App\Controllers;
use App\Models\{Job, Project};

class IndexController extends BaseController
{
    public function indexAction() {
        //Get all jobs from DB
        $jobs = Job::all();
        //Assign name for portfolio
        $name = 'Axel Espinosa';
        $limitMonths = 2000;
        //Call the views
        echo $this->renderHTML('index.twig', [
            'name' => $name,
            'jobs' => $jobs
        ]);
    }

}