<?php

namespace App\Http\Controllers;

use App\CaseStudy;
use App\CaseStudyCategory;
use App\ContactForm;
use Illuminate\Http\Request;

class FrontController
{
    public function index()
    {
        return view('welcome');
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
                        $q->where('case_study_category_id', '=', $category); 
                    })
                    ->get();

        return response()->json([ 'data' => ($records ? $records : []) ]); 
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
}
