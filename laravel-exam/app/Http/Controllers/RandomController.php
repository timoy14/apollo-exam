<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Randoms;
use App\Models\Breakdowns;

class RandomController extends Controller
{
    public function index(){
    	$this->store();
    	$Randoms = Randoms::where("flag",0)->get("id");
    	$random_ids = [];
    	$string = "";
    	foreach ($Randoms as $Random) {
    		$random_ids[] = $Random->id;
    		$Breakdowns = Breakdowns::where("random_id",$Random->id)->get();
    		foreach ($Breakdowns as $Breakdown) {
    			$string .= $Breakdown->values.",";
    		}
    	}
    	Randoms::whereIn("random",$random_ids)->update(["flag"=>1]);
    	return $string;
    }

    public function store(){
    	
    	for ($i=0; $i < rand(5,10) ; $i++) { 
    		sleep(2);
    		$Randoms = new Randoms();
    		$Randoms->values = Str::random();
    		$Randoms->save();
    		$random_id = $Randoms->id;
    		for ($i=0; $i < rand(5,10); $i++) { 
    			sleep(2);
				$string  = $this->generateRandomString(5);
				Breakdowns::insert(["random_id"=>$random_id,"values"=>$string]);
    		}
    	}
    }

	protected function generateRandomString($length = 25) {

 		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}
