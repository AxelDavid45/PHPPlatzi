<?php


namespace App\Controllers;

use App\Models\Job;
use Respect\Validation\Validator as v;

class JobsController extends BaseController
{
    public function getAddJobAction($request)
    {
        $responseMessage = null;
        //Verify if isn't empty
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $jobValidator = v::key('title', v::stringType()->notEmpty())
                ->key('description', v::stringType()->notEmpty());

            try {
                //Try to validate
                $jobValidator->assert($postData);
                //Retrieve uploaded files
                $files = $request->getUploadedFiles();
                //Save logo
                $logo = $files['logo'];
                //Do not need to have error
                if ($logo->getError() == UPLOAD_ERR_OK) {
                    //Generate a unique filename
                    $fileName = time() . $logo->getClientFileName();
                    //Save in public/uploads
                    $logo->moveTo("uploads/$fileName");

                    //Save a new object in database
                    $job = new Job(); //Instance from Job Model
                    //Fill data
                    $job->title = $postData['title'];
                    $job->description = $postData['description'];
                    $job->image = $fileName;
                    //Save it in database
                    $job->save();

                    $responseMessage = 'Saved';
                } else {
                    $responseMessage = $logo->getError();
                }

            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }

        }

        //Return a render
        return $this->renderHTML('addJob.twig', [
            'message' => $responseMessage,
        ]);

    }
}