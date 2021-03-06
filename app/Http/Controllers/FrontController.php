<?php

namespace App\Http\Controllers;

use App\CaseStudy;
use App\CaseStudyCategory;
use App\FormSubmission;
use App\Mail\EnquirySubmission;
use App\ContactForm;

use NZTim\Mailchimp\Mailchimp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class FrontController
{
    public function index()
    {
        return view('welcome')->with('slug', '/');
    }

    public function fetchData( $type = 1 )
    {
    	$returnData = false;

    	switch ($type) {
    		case 1: $returnData = CaseStudyCategory::whereNotIn('id', [12, 13])->get();
    			break;
    	}

    	return response()->json([ 'data' => $returnData ]);
    }

    /**
    * Fetch records via case study category
    * @param int $category
    *
    * @return \Illumninate\Http\Response
    **/
    public function fetchByCategory( $category )
    {
        $records = CaseStudy::with('categories')
                    ->where('published', true)
                    ->whereHas('categories', function($q) use ($category){
                        if ( $category > 0 ){
                            $q->where('case_study_category_id', '=', $category); 
                        } else {
                            $q->whereIn('case_study_category_id', [12, 13]);
                        }
                    })
                    ->get();

        return response()->json([ 'data' => ($records ? $records : []) ]); 
    }

    public function fetchCaseStudiesPosts()
    {
        $categories = CaseStudyCategory::whereIn('id', [12, 13])->get();
        $case_studies = CaseStudy::with('categories')->where('published', true)
                        ->whereHas('categories', function($q){
                            $q->whereIn('case_study_category_id', [12, 13]); 
                        })
                        ->get();

        return response()->json([ 'case_studies' => $case_studies, 'categories' => $categories ]);
    }

    /**
    * Get contact form record via id
    *
    * @param int $id
    * @return json $data;
    **/
    public function getContactForm( $id )
    {
        return response()->json([ 'data' => ContactForm::find($id) ]);
    }

    public function sendContactEmail( Request $request )
    {
        $contactSubmission = FormSubmission::create($request->all());

        $sendMail = Mail::to(env('MAIL_TO_ADDRESS'))->bcc( 'petya@digitalpie.co.uk', 'Petya' )->send(new EnquirySubmission($contactSubmission));

        return response()->json([ 'mail_sent' => ( Mail::failures() ? false : true ) ]);
    }

    public function sendNewsletter( Request $request )
    {   
        $mc = new Mailchimp(env('MC_KEY'));

        $mc->subscribe(env('MC_LIST_ID'), $request->input('email'));
            
        return response()->json([ 'status' => true]);
    }
}
