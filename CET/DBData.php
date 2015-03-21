<?php


class getDBData{
    
    
    private function getData($query,$type){
    
        //DB Connection Info
        //用户名　 : SAE_MYSQL_USER
        //密　　码 : SAE_MYSQL_PASS
        //主库域名 : SAE_MYSQL_HOST_M
        //从库域名 : SAE_MYSQL_HOST_S
        //端　　口 : SAE_MYSQL_PORT
        //数据库名 : SAE_MYSQL_DB
        
        $mysqli = new mysqli(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS, SAE_MYSQL_DB);
        
        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        if($type==1){
            //search
            //echo $query
            $result = $mysqli->query($query);
        }
        return $result;
        /* close connection */
        $mysqli->close();

    }

   
    public function getCETScore($numText,$keywordFlag){
        
        If ($keywordFlag =="CET1"){
        	$subSql="si.uID=".$numText;
        }else if($keywordFlag =="CET2"){
        	$subSql="ci.admissionNum=".$numText;             
        }else if($keywordFlag =="CET3"){
        	$subSql="si.Id_Student=".$numText;             
        }
        
        $query = "	Select subT.StudentName as StudentName,
                            subT.examDate as examDate,
                            subT.examType as examType,
                            score.*	
				From	(	Select 	ci.admissionNum as admissionNum, 
                                    si.StudentName as StudentName,
                                    ci.examType as examType,
                                    ci.examDate as examDate	
                            from `CET_Info` ci,`Student` si
                            where ci.uID=si.uID
                            and ".$subSql." ) `subT`  left join `score`
                            on subT.admissionNum = score.admissionNum";
        $result =$this->getData($query,1);
        return $result;
    }


}

?>