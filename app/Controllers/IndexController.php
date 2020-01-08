<?php
namespace App\Controllers;
use App\Models\{Job, Project};

class IndexController
{
    public function indexAction() {
        //Get all jobs from DB
        $jobs = Job::all();
        //Assign name for portfolio
        $name = 'Axel Espinosa';
        $limitMonths = 2000;
        //Call the view
        require_once '../view/index.php';
    }

}