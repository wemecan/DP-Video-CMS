<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseStudyCategory extends Model
{
    protected $table = 'case_study_categories';
    protected $fillable = [ 'title', 'slug', 'description' ];

    public function case_studies()
    {
        return $this->belongsToMany('App\CaseStudy')->withTimestamps();
    }

}
