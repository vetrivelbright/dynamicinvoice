<?php
include("dbconfig.php");
$fnsout= new myDBC();
include("mpdf/mpdf.php");
require_once 'zipper_class.php';


date_default_timezone_set('Asia/Calcutta');



$now = time();
$random = substr(md5(mt_rand(1,$now)), 0, 20);;
 $rand_op =  strtoupper($random);

$result = mkdir($rand_op);

if($result)
{
echo "folder created";
}
else{
echo "error while create folder";
}


 //$create_dtm_folder = date('Y-m-d-H-i-s');


//$result = mkdir($create_dtm_folder);

$get_csv_file_count = $_POST['csv_file_count'];

	



  


$Query="select * from invoice_tbl order by inv_pk desc limit $get_csv_file_count";
$checkout_result=$fnsout->SingleQuery($Query);
 //$aa = $checkout_result->inv_num;


 while($checkout_row=mysqli_fetch_object($checkout_result)){
		 $inv_nums=$checkout_row->inv_num;
		 $inv_dates=$checkout_row->inv_date;
		 $inv_fnames=$checkout_row->inv_fname;
		 $inv_lnames=$checkout_row->inv_lname;
		 $inv_emails=$checkout_row->inv_email;
		 $inv_amount_usds=$checkout_row->inv_amount_usd;
		 




 $pdf1 = '
<div id="page_1" class="pdf_page">
<h1 class="tax_invoice">Invoice</h1>
<div class="page_1">

<div class="billing_invoice">
<div class="billing_details">
<h2 class="company_name">KUDOMETRICS TECHNOLOGIES PVT. LTD.</h2>
<p class="kudo_add">Sf.No.524/3, SC Main Road, Vinayagapuram,
Narasingapuram, Attur Tk, Salem Dt.<br/>
GSTIN/UIN: 33AAGCK3780L1Z9<br/>
Company PAN: AAGCK3780L<br/>
State Name : Tamil Nadu, Code : 33<br/>
CIN: U72900TZ2016PTCO27687<br/>
E-Mail : sales@kudometrics.com</p>

<p class="buyer_details">Buyer (Billto)</p>
<h2 class="buyer_name">'.$inv_fnames.' '.$inv_lnames.'</h2>
<p class="kudo_add">
Email: '.$inv_emails.'<br/>

</p>


</div>

<div class="invoice_details">

<p><b>Invoice No :</b> '.$inv_nums.'</p>
<p style="margin-top:10px;"><b>Dated :</b>'.$inv_dates.'</p>

</div>

</div>


<div class="table">
  <div class="table-row">
    <div class="table-cell" style="width:35px;font-weight:bold;border-left: none;">
     S.No
    </div>
    <div class="table-cell" style="width:198px;font-weight:bold;">
      Description
    </div>
    <div class="table-cell" style="width:120px;font-weight:bold;">
      Unit Cost (USD)
    </div>
    <div class="table-cell" style="width:95px;font-weight:bold;">
      Quantity
    </div>
    <div class="table-cell" style="width:120px;font-weight:bold;border-right: none;">
     Amount (USD)
    </div>
  </div>
  <div class="table-row">
    <div class="table-cell" style="width:35px;height:200px;border-left: none;">
     1
    </div>
    <div class="table-cell" style="width:198px;height:200px;">
      Saas Service Charge
    </div>
    <div class="table-cell" style="width:120px;height:200px;">
      '.$inv_amount_usds.'
    </div>
    <div class="table-cell" style="width:95px;height:200px;">
      1
    </div>
    <div class="table-cell" style="width:120px;height:200px;border-right: none;">
     '.$inv_amount_usds.'
    </div>
  </div>
  <div class="table-row">
    <div class="table-cell" style="width:35px;border-left: none;">
     &nbsp;
    </div>
    <div class="table-cell" style="width:198px;">
     &nbsp;
    </div>
    <div class="table-cell" style="width:120px;">
    &nbsp;
    </div>
    <div class="table-cell" style="width:95px;font-weight:bold;text-align:right;">
     Total (USD)
    </div>
    <div class="table-cell" style="width:120px;font-weight:bold;border-right: none;">
     '.$inv_amount_usds.'
    </div>
  </div>
</div>


<div class="signature">


<div style="text-align:right;">
<h3 class="sign_company">for KUDOMETRICS TECHNOLOGIES PVT. LTD.</h3><br/>
<img src="sign.jpg" width="200px;" /><br/>
<p>Authorised Signator</p>
</div>

</div>

</div>
<p class="cominfo">This is a Computer Generated Invoice</p>
</div>
';






//$a = "ST/21-22/001";
 $paths = str_replace("/","-",$inv_nums);
//$filename1="abc.pdf";
//$paths = rand();
$file_path = "$rand_op/".$paths.".pdf";
//$filename1=rand();


        $mpdf=new mPDF('c');
		$stylesheet = file_get_contents('inv_pdf.css');
        $mpdf->WriteHTML($stylesheet,1); // The parameter 1 tells that this is css/style only and no body/html/text
		//write html to PDF

          $pdf1 = mb_convert_encoding($pdf1, 'UTF-8', 'UTF-8');
        $mpdf->WriteHTML($pdf1);
		//output pdf
		$mpdf->debug = true;
		
       //$mpdf->Output("$filename",'S');
	   //$mpdf->Output();
		$mpdf->Output($file_path,'F');
}



// Include and initialize ZipArchive class

$zipper = new ZipArchiver;




// Path of the directory to be zipped
$dirPath = "$rand_op";

// Path of output zip file
$filename = "inv-".$rand_op.".zip";

// Create zip archive
$zip = $zipper->zipDir($dirPath, $filename);

if($zip){
    echo 'ZIP archive created successfully.';
}else{
    echo 'Failed to create ZIP.';
}




// Download Created Zip file



   if (file_exists($filename)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
    header('Content-Length: ' . filesize($filename));





    flush();
    ob_end_clean();
    //ob_end_flush();
    readfile($filename);
    // delete file
    chmod($filename, 0644);
    //if(unlink($filename)) echo "Deleted file ";
    unlink($filename);
    

}


delete_files($rand_op);

/* 
 * php delete function that deals with directories recursively
 */
function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file ){
            delete_files( $file );      
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}

?>