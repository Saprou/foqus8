<?php session_start(); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<title>Uploading Data cv....</title>

<style>
    .bs-example {
        margin: 20px;
    }

</style>




<?php
require_once '../assets/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

include_once('../lib/db.php');
$SelectMeetingInfo="SELECT TOP(1) SYMBOL + 'Log_'+ CONVERT(nvarchar,login_allowed_time,6) as uploadfolder from Co_info" ;
$Query = $FoQusdatabase ->Select($SelectMeetingInfo);
foreach($Query as $FetchInfo){
	$SYMBOLLog=$FetchInfo['uploadfolder'];
}
$doc_root = $_SERVER["DOCUMENT_ROOT"]; 
$uploads_dir_Log = $doc_root.'/../uploads/'.$SYMBOLLog."/";

require_once($doc_root."/../assets/vendor/autoload.php");
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
$logger = new Logger('Admin');
$logger->pushHandler(new StreamHandler($uploads_dir_Log . '/../adminlog.log', Logger::DEBUG));
function getUserIP()
	{
		$ipaddress = '';
	if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = reset(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
	else if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];    
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
		return $ipaddress;
    //return filter_var($ipaddress, FILTER_VALIDATE_IP);

		
	}
$ipaddress = getUserIP();


if(isset($_POST["action"]) && $_POST["action"] == "update_record_with_share"){
    
    //echo "Ajay";
    //exit;
    
    if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "" ) {
        
        $allowedExtensions = array("xls","xlsx");
        // Return the extension of the file
        $ext = pathinfo( $_FILES['uploadFile']['name'] , PATHINFO_EXTENSION );
        if (in_array ($ext, $allowedExtensions)){
            $isUploaded = $_FILES['uploadFile']['tmp_name'];
            if ($isUploaded) {
               // include "Classes/PHPExcel/IOFactory.php";
                try {
                    //$objPHPExcel = PHPExcel_IOFactory::load($isUploaded);
					$objPHPExcel = IOFactory::load($isUploaded);
                } catch (Exception $e) {
                    die ( 'Error loading file "' . pathinfo($isUploaded, PATHINFO_BASENAME ) . '": ' . $e->getMessage());
                }
                $sheet = $objPHPExcel->getSheet(0);
                $total_rows = $sheet->getHighestRow();
                $highest_column = $sheet->getHighestColumn();
                $upload_error;
                $success_query;
                
                $headings = $sheet->rangeToArray('A1:' . $highest_column . 1, NULL, TRUE, FALSE);
                for ($row = 2 ; $row <= $total_rows; $row++) {
                    $rowData = $sheet-> rangeToArray ('A' . $row . ':' . $highest_column . $row, NULL, TRUE, FALSE);
                    $columnData = $sheet-> rangeToArray ('A' . $row . ':' . $highest_column . $row, NULL, TRUE, FALSE);
                    
                    $i_holder = $columnData[0][0]; // added by kamal
                    $shares = $columnData[0][1];
					$email = trim($columnData[0][2]);
                    $mobile_phone = $columnData[0][3];
                    $proxy_name = $columnData[0][4];
                    $proxy_type = $columnData[0][5];
                    
                    if($i_holder == "" || $email == ""){
                        $upload_error .= "<p>".$i_holder." Account ID and Email Address is Mandatory</p>";
                    }else{
                        
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) { 
                            if($shares != ""){
                            $check_row = "SELECT q_share FROM EGM WHERE i_holder = '".$i_holder."'";
                            $params = array();
                           $stmt1 = $FoQusdatabase ->Select($check_row,$params);
						   foreach($stmt1 as $stmt){
							   $q_share=$stmt['q_share'];
							   }
                                if($shares == $q_share ){
                                
                                    if($proxy_name == "" && $proxy_type == ""){
                                        $success_query .= "UPDATE EGM SET e_mail= '".$email."', m_phone = '".$mobile_phone."', ApprovedforOnline='Y' Where i_holder='$i_holder';";
                                    }elseif($proxy_name != "" && $proxy_type == ""){ $upload_error .= "<p>".$i_holder." Proxy Type Missing</p>"; }
									elseif($proxy_name == "" && $proxy_type != ""){ $upload_error .= "<p>".$i_holder." Proxy Name Missing</p>"; }
									elseif($proxy_name != "" && $proxy_type != ""){
                                        if($proxy_type != "A" && $proxy_type != "B" && $proxy_type != "C"){
                                            $upload_error .= "<p>".$i_holder." Invalid Proxy Type</p>";
                                        }else{
                                            $success_query .= "Update EGM set e_mail ='".$email."', m_phone = '".$mobile_phone."', Proxy_name = N'$proxy_name', Proxy='Y', ProxyType = '$proxy_type', ApprovedforOnline='Y' where i_holder = '$i_holder';";
                                        }
                                    }
                                    
                                } // check & count q_share Value   
                                else{
                                    $upload_error .= "<p>".$i_holder." Invalid share</p>";
                                }
                                
                                
                                } // Check Share blank Value 
                            else{
                                    $upload_error .= "<p>".$i_holder." Share value is Mandatory</p>";
                                }
                            
                            
                        
                        
                        
                        
                        } // Account ID & Email Address is Mandatory 
                        else{
                            $upload_error .= "<p>".$email." is not a valid email address</p>";
                        } 
                    } 
                }
                
                //echo $upload_error."<br>";
                
               // exit;
                
                
                
                if($upload_error != ""){ ?>

<!-- Modal -->
<div class="bs-example">
    <div id="modalCenter" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uploading Error List</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?php echo $upload_error; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='../approve-online-joiners.php';">OK, Got it!</button>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    $("#modalCenter").modal('show');
    //$('#modalCenter').modal('show');

</script>
<?php
                                        
                }
               
               else{
                    $stmtCompany = $FoQusdatabase ->run($success_query);
                    if($stmtCompany) {
                        $_SESSION['msg'] = "File is uploaded Successfully";
						$logger->Info('Online Joiners Approved ',['user'=>$_SESSION['uname'],'IP'=>$ipaddress]);
                        echo "<script type=\"text/javascript\"> window.location.href='../approve-online-joiners.php';</script>";
                    }else {
                        $_SESSION['msg'] = "Invalid Value";
                        echo "<script type=\"text/javascript\"> window.location.href='../approve-online-joiners.php';</script>";
                    }
                } 
            } else {
                $_SESSION['msg'] = "File not uploaded";
                echo "<script type=\"text/javascript\"> window.location.href='../approve-online-joiners.php';</script>";
            }
        } else {
            $_SESSION['msg'] = "This type of file not allowed!";
            echo "<script type=\"text/javascript\"> window.location.href='../approve-online-joiners.php';</script>";
        } ?>
<?php
    
    }
    else {
        $_SESSION['msg'] = "Select an excel file first!";
        echo "<script type=\"text/javascript\"> window.location.href='../approve-online-joiners.php';</script>";
    } 


}

else{
    $_SESSION['msg'] = "invalid";
        echo "<script type=\"text/javascript\"> window.location.href='../approve-online-joiners.php';</script>";
}

?>
