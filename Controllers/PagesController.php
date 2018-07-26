<?php

namespace Controllers;

class PagesController
{
    public function postRequest()
    {
        $payload = json_decode(file_get_contents("php://input"));

        $nextStepList = [
            "Some Schooling (disabled)" => "Next step: Inadequate Experience",
            "Grade 9" => "Next step: Inadequate Experience",
            "Grade 10" => "Next step: Inadequate Experience",
            "Grade 11" => "Next step: Dependent on Results",
            "Grade 12 / Matric (disabled)" => "Dependent on Results",
            "Certificate" => "Next step: Establish Level",
            "Diploma" => "Next step: Longlist",
            "Degree" => "Next step: Shortlist Candidate",
            "Honours" => "Next step: Shortlist Candidate",
            "Professional Qualification" => "Next step: Shortlist Candidate",
            "Masters" => "Next step: Shortlist Candidate",
            "Doctorate"  => "Next step: Shortlist Candidate",
        ];

        http_response_code(200);

        header('Content-Type: application/json');

        $weightingRange = [
            "0|25" => " is inadequate for this position",
            "26|60" => " is dependent on additional information",
            "61|100" => " is highly desirable"
        ];

        $QualificationMessage = "Your selected qualification of";

        $percentage  = $this->calcPerc($payload->select, count((array)$payload->selectList));

        $selectList =(array)$payload->selectList;

        $response = '';

        foreach ($weightingRange as $key => $weightingMessage) {
            $range = explode("|", $key);

            if ($percentage >= (int)$range[0] && $percentage <= (int)$range[1]) {
                $nextStep = '';
                if(array_key_exists(trim($selectList[$payload->select]), $nextStepList)) {
                    $nextStep = $nextStepList[trim($selectList[$payload->select])];
                }

                $response =  $QualificationMessage . " <strong>" .trim($selectList[$payload->select])  . "</strong>" . $weightingMessage .
                    ".\n".$nextStep;
            }
        }

        print_r(json_encode($response));
    }

    public function getRequest()
    {
        http_response_code(200);

        $resourceFile = "resourceFile.txt";
        $content = fopen($resourceFile, 'r');
        $data = fread($content, filesize($resourceFile));
        $response = [];
        $cleanArray = explode("\n", $data);

        foreach($cleanArray as $line)
        {
            $tmp = explode(".", $line);
            $response[$tmp[0]] = $tmp[1];
        }

        fclose($content);

        print_r(json_encode($response));
    }

    public function otherRequest()
    {
        http_response_code(200);

        $response = [
            'status' => false,
            'code' => '405',
            'message' => 'Method Not Allowed'
        ];
    }

    private function calcPerc($weight, $totalCount)
    {
        return ((int) $weight / (int) $totalCount) * 100;
    }
}
