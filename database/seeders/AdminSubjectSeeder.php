<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['stream'=>'Science','title' => 'Botany'],
            ['stream'=>'Science','title' => 'Zoology'],
            ['stream'=>'Science','title' => 'Microbiology'],
            ['stream'=>'Science','title' => 'Biotechnology'],
            ['stream'=>'Science','title' => 'Biochemistry'],
            ['stream'=>'Science','title' => 'Life Sciences'],
            ['stream'=>'Science','title' => 'Biostatistics'],
            ['stream'=>'Science','title' => 'Food and nutrition'],
            ['stream'=>'Science','title' => 'Environmental Sciences'],
            ['stream'=>'Science','title' => 'Chemistry'],
            ['stream'=>'Science','title' => 'Physics'],
            ['stream'=>'Science','title' => 'Mathematics'],
            ['stream'=>'Science','title' => 'DMLT'],
            ['stream'=>'Science','title' => 'Nursing'],
            ['stream'=>'Science','title' => 'Pharmacy'],
            ['stream'=>'Science','title' => 'Home Science'],
            ['stream'=>'Science','title' => 'Forensic Science'],

            ['stream'=>'Commerce','title' => 'Advanced accountancy and auditing'],
            ['stream'=>'Commerce','title' => 'Advanced statistics'],
            ['stream'=>'Commerce','title' => 'Advanced business management'],
            ['stream'=>'Commerce','title' => 'Computer application'],
            ['stream'=>'Commerce','title' => 'Banking'],
            ['stream'=>'Commerce','title' => 'Banking and insurance'],
            ['stream'=>'Commerce','title' => 'International business'],
            ['stream'=>'Commerce','title' => 'Marketing'],
            ['stream'=>'Commerce','title' => 'Accounting'],
            ['stream'=>'Commerce','title' => 'Business Management'],
            ['stream'=>'Commerce','title' => 'Banking and finance'],
            ['stream'=>'Commerce','title' => 'Computer science'],
            ['stream'=>'Commerce','title' => 'Banking and co-operation'],
            ['stream'=>'Commerce','title' => 'Co-operation'],
            ['stream'=>'Commerce','title' => 'Accountancy'],
            ['stream'=>'Commerce','title' => 'Human Resource Management'],
            ['stream'=>'Commerce','title' => 'Statistics'],
            ['stream'=>'Commerce','title' => 'Business economics'],
            ['stream'=>'Commerce','title' => 'Accounting and financial management'],
            ['stream'=>'Commerce','title' => 'Commerce and business management'],
            ['stream'=>'Commerce','title' => 'Co-operative management and rural studies'],
            ['stream'=>'Commerce','title' => 'Psychology'],

            ['stream'=>'Arts','title' => 'Political science'],
            ['stream'=>'Arts','title' => 'English'],
            ['stream'=>'Arts','title' => 'Hindi'],
            ['stream'=>'Arts','title' => 'Sanskrit'],
            ['stream'=>'Arts','title' => 'History'],
            ['stream'=>'Arts','title' => 'Gujarati'],
            ['stream'=>'Arts','title' => 'Psychology'],
            ['stream'=>'Arts','title' => 'Economics'],
            ['stream'=>'Arts','title' => 'Intellectual Pproperty right '],
            ['stream'=>'Arts','title' => 'Sociology'],
            ['stream'=>'Arts','title' => 'Computer Application'],
            ['stream'=>'Arts','title' => 'Law'],
            ['stream'=>'Arts','title' => 'Geography'],
            ['stream'=>'Arts','title' => 'Linguistics'],


        ];

        foreach ($arr as $list){
            if(subjects()->where('title',$list['title'])->count() == 0){
                $stream_uuid = streams()->where('title',$list['stream'])->value('uuid');
                $list['slug'] =Str::slug($list['title']);
                unset($list['stream']);
                $list['stream_uuid'] = $stream_uuid;
                subjects()->create($list);
            }
        }
    }
}
