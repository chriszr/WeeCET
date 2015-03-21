<?php
require_once "./CET/DBData.php";

class gradeQuery{
    public $returntext;
    
    public function get_cet_score($contentText,$keywordFlag){
        //分割出消息当中的号码内容
        $numText = trim(strstr($contentText,"#"),"#");
        $isNumValid = $this->numText_validation(trim($numText),$keywordFlag);
        if($isNumValid){
            //进行数据库查询
            //return 成绩的文本
            $data = new getDBData();
            $result = $data->getCETScore($numText,$keywordFlag);
            $returnText = $this->convertResult($result);
            if (strlen($returnText) == 0){
                return "找不到相关记录"."\n号码：".$numText;
            }
            return "考生信息"."\n".$returnText;
        }else {

            return "号码有误，请核对！"."\n".$numText;
        }
        
    }


    private function numText_validation($numText,$keywordFlag){
        
        if ($keywordFlag == "CET1" && strlen($numText) == 18){
            
            return true;
            
        }else if ($keywordFlag == "CET2" && strlen($numText) == 15){
            
            return true;
            
        }else if ($keywordFlag == "CET3" && strlen($numText) == 9){
            
            return true;
            
        }else{
            return false;
            
        }
    }
    
    private function convertResult($result){
        if ($result){
            $tempText = "";
            while ($obj = $result -> fetch_object()){
                $stuName = $obj -> StudentName;
                $examDate = $obj -> examDate;
                $examType = $obj -> examType;
                $admissionNum = $obj -> admissionNum;
                $totalscore = $obj -> totalscore;
                $listeningscore = $obj -> listeningscore;
                $readingscore = $obj -> readingscore;
                $writingscore = $obj -> writingscore;
                $isAbsent = $obj -> isAbsent;
                $isCheat = $obj -> isCheat;
                $tempText = "\n姓名：".$stuName."\n考试时间：".$examDate."\n考试类型：".$examType."\n准考证号：".$admissionNum."\n总分：".$totalscore."\n听力：".$listeningscore."\n阅读：".$readingscore."\n写作：".$writingscore."\n是否缺考：".$isAbsent."\n是否作弊：".$isCheat;
                
            }
        
        return $tempText;
        }else {
            return "找不到相关记录";
        }
    }

}

?>