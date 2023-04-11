<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Ip_table;

class UtilController extends Controller {


    /*
        method : POST
        request : ip
        return : Country Code
    */
    public function request_get_country(Request $request) {

        $result = null;
        $status = 400;

        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip',
        ]);

        if ($validator->fails()) {
            return $this->common_result("Bad Request Check your IP");
        }

        try {

            $request_ip = $request->input('ip');
            $client_ip = $request->getClientIp();

            if($request_ip == $client_ip) {

                // search ip
                $res = Ip_table::getCountryCode($request_ip);

                if(!empty($res)) {
                    $result = $res['code'];
                    $status = 200;
                } else {
                    // not found ip
                    $result = 'failed matching IP not found';
                }

            } else {
                $result = "Error. can not matching IP. Please request your IP number";
            }


        } catch (\Exception $e) {
            $result = 'Error. Dev : '.$e;
        }

        return $this->common_result($result, $status);
    }



    /* */
    public function upload_ip_table(Request $request) {

        ini_set('max_execution_time', 300);
        $ms = '';

        try {

            $handle = fopen($request->file('csv_file')->getPathname(), 'r');
            $header = fgetcsv($handle);
            $data = [];

            $file = $request->file('csv_file');
            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle);
            $data = [];

            $count = 0;
            while (($row = fgetcsv($handle)) !== false) {
                $data[] = array_combine($header, $row);

                DB::table('ip_table')->insert([
                    'reg_date' => $data[$count]['init'],
                    'last_date' => $data[$count]['last'],
                    'code' => $data[$count]['code'],
                    'to_ip' => $data[$count]['to_ip'],
                    'end_ip' => $data[$count]['end_ip'],
                    'prefix' => $data[$count]['prefix']
                ]);

                $count++;
            }

            fclose($handle);

        } catch (\Exception $e) {
            $msg = 'error. '.$e;
        }

        return response()->json(['message' => $msg]);

    }


    /* commons */
    private function common_result($result = [], $status = 400) {
        return response()->json([
            'result' => $result,
            'status' => $status
        ], $status);
    }
}