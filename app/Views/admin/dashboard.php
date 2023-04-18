<?php include("header.php"); ?>
<main>

<div class="container-fluid" style="margin-bottom:61px;">
         
    <div class="top-menu" style="padding: 10px;">
        <figure class="logo-wrap">
            <span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>"></span>
        </figure>
    </div>
      
</div>

<div class="main-dashboard-body">
    <?php include("navbar.php"); ?>
    
    <div class="dashboard-content col-10">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Document Title</th>
                <th scope="col">No of Signers</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(!empty($documents)){
                        
                        foreach($documents as $k => $tmpDoc){
                            $sr = $k + 1;
                            $uploadId = $tmpDoc["uploadId"];
                            $documentId = $tmpDoc["documentId"];
                            $noOfParties = $tmpDoc["no_of_parties"];
                            $documentPath = $tmpDoc["documentPath"];
                            $isComplete = $tmpDoc["isComplete"];
                            $createdAt = $tmpDoc["created_at"];
                            $docDetails = $tmpDoc["docDetails"];
                            $fileName = $docDetails["file_name"];
                            $docTitle = $docDetails["documentTitle"];

                            if(!$docTitle || $docTitle == ""){
                                $docTitle = $fileName;
                            }

                            if($isComplete == 1){
                                $status = "Complete";
                            }else{
                                $status = "Pending";
                            }
                    
                            $tr .= '<tr>
                                <th scope="row">'.$sr.'</th>
                                <td>'.$docTitle.'</td>
                                <td>'.$noOfParties.'</td>
                                <td>'.$createdAt.'</td>
                                <td>'.$status.'</td>
                                <td>
                                    <a href="'.base_url($documentPath).'" target="_blank">View Document</a>
                                    <a href="'.site_url("documentdetails/".$documentId).'" target="_blank">Details</a>
                                </td>
                            </tr>';    

                        }
                    }else{
                        $tr = '<tr><td colspan="6">It seems that you have not created any document yet.</td></tr>';
                    }

                    echo $tr; 
                ?>

            </tbody>
        </table>
    
    </div>
</div>

</main>

<?php include("footer.php"); ?>