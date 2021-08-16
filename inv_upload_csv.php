<?php

error_reporting(E_ALL);
use Phppot\DataSource;

require_once 'config_csv_upload.php';
$db = new DataSource();
$conn = $db->getConnection();

if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];

    $file_csv = file($fileName);
    $csv_row_count = count($file_csv);

    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        
        
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            
            $inv_date = "";
            if (isset($column[0])) {
                $inv_date = mysqli_real_escape_string($conn, $column[0]);
            }
            $inv_num = "";
            if (isset($column[1])) {
                $inv_num = mysqli_real_escape_string($conn, $column[1]);
            }

            $inv_fname = "";
            if (isset($column[2])) {
                $inv_fname = mysqli_real_escape_string($conn, $column[2]);
            }

            $inv_lname = "";
            if (isset($column[3])) {
                $inv_lname = mysqli_real_escape_string($conn, $column[3]);
            }

            $inv_email = "";
            if (isset($column[4])) {
                $inv_email = mysqli_real_escape_string($conn, $column[4]);
            }

            $inv_amount_usd = "";
            if (isset($column[5])) {
                $inv_amount_usd = mysqli_real_escape_string($conn, $column[5]);
            }
            
            
            $sqlInsert = "INSERT into invoice_tbl (inv_date,inv_num,inv_fname,inv_lname,inv_email,inv_amount_usd)
                   values (?,?,?,?,?,?)";
            $paramType = "ssssss";
            $paramArray = array(
                $inv_date,
                $inv_num,
                $inv_fname,
                $inv_lname,
                $inv_email,
                $inv_amount_usd

               
            );
            $insertId = $db->insert($sqlInsert, $paramType, $paramArray);
            
            if (! empty($insertId)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message = "Problem in Importing CSV Data";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
<script src="jquery-3.2.1.min.js"></script>

<style>
body {
    font-family: Arial;
    width: 550px;
}

.outer-scontainer {
    background: #F0F0F0;
    border: #e0dfdf 1px solid;
    padding: 20px;
    border-radius: 2px;
}

.input-row {
    margin-top: 0px;
    margin-bottom: 20px;
}

.btn-submit {
    background: #333;
    border: #1d1d1d 1px solid;
    color: #f0f0f0;
    font-size: 0.9em;
    width: 100px;
    border-radius: 2px;
    cursor: pointer;
}

.outer-scontainer table {
    border-collapse: collapse;
    width: 100%;
}

.outer-scontainer th {
    border: 1px solid #dddddd;
    padding: 8px;
    text-align: left;
}

.outer-scontainer td {
    border: 1px solid #dddddd;
    padding: 8px;
    text-align: left;
}

#response {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 2px;
    display: none;
}

.success {
    background: #c7efd9;
    border: #bbe2cd 1px solid;
}

.error {
    background: #fbcfcf;
    border: #f3c6c7 1px solid;
}

div#response.display-block {
    display: block;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $("#frmCSVImport").on("submit", function () {

	    $("#response").attr("class", "");
        $("#response").html("");
        var fileType = ".csv";
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#file").val().toLowerCase())) {
        	    $("#response").addClass("error");
        	    $("#response").addClass("display-block");
            $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
            return false;
        }
        return true;
    });
});
</script>
</head>

<body>

<h2>Create Dynamic PDF Invoice Web App</h2>
    <h2>Import Your CSV file & Download Your PDF</h2>

    <div id="response"
        class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
        <span>
        <?php if(!empty($message)) { echo $message; } 
        
        if (! empty($insertId)) {
        ?>
        </span>
            <br />



        <div class="row">

            <form class="form-horizontal" action="final_pdf.php" method="post"
                name="download_csv" id="download_csv"
                >
                <div class="input-row">
                     <input type="hidden" name="csv_file_count"
                        id="csv_file_count" value="<?php echo $csv_row_count;?>">
                    <button type="submit" id="submit" name="download_imgs"
                        class="btn-submit1">Click to Create & Download PDF</button>
                    <br />
                    <br />


                   

                </div>

                <a href="inv_upload_csv.php">  Home</a>

            </form>
        </div>

        <?php } ?>
</div>



        
        </div>

        <?php
         if (empty($insertId)) { ?>
    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport"
                enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Choose CSV
                        File</label> <input type="file" name="file"
                        id="file" accept=".csv">
                    <button type="submit" id="submit" name="import"
                        class="btn-submit">Import</button>
                    <br />

                </div>

                <div>

                <a href="sample_inv1.csv">  Download Sample CSV File</a>
</div>

            </form>
</div>
</div>
<?php } ?>

  

</body>

</html>