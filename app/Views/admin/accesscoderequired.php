<?php include("header.php"); ?>
<main>
<form class="access-code-form row row-cols-lg-auto g-3 align-items-center" action="javascript:void(0);">

    <div class="col-12">
        <div class="content-box">
            <div class="img-box">
                <img src="<?php echo base_url("/assets/images/accesscode.svg")?>">
            </div> 
            <div> 
                <div class="authenticationDialog-header">
                <?php
                    if($authType == 1){
                        echo "OTP required to sign this document";
                    }else{
                        echo "Access code required to sign this document";
                    }
                ?>

                </div>
                <div class="authenticationDialog-sub-header">
                <?php
                    if($authType == 1){
                        echo "Please enter the one time password (OTP) that was sent to your email";
                    }else{
                        echo "Please enter the access code provided by the sender";
                    }
                ?>        
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-8 access-code-row">
        <div class="input-group">
            <input type="text" class="form-control" id="accesscodeInput" placeholder="<?php if($authType == 1){ echo "OTP*"; }else{echo "Access Code*";}?>" maxlength="6">
        </div>
        <span id="otp-expires-in" class="bs-invalid-accesscode-text otp-expires-in" style="float: left;">Expires in</span>
        <span id="invalid-accesscode-text" class="bs-invalid-accesscode-text show"><?php if($authType == 1){ echo "Invalid OTP"; }else{ echo "Invalid access code"; }?></span>
    </div>


    <div class="col-4 access-code-button-row">
        <button onclick="reviewDocument();" type="submit" class="btn btn-primary col-12" id="reviewButton">Review document</button>
        <span class="bs-remainingAuth-text show">2 attempts remaining</span>
    </div>
    <div class="access-code-footer-note">
        <span class="bs-note-style" style="display:none;">Note: Document will be locked after 3 failed attempts.</span>
        <div class="bs-link-add-info-container text-center col-12">
        <?php if($authType == 1){ ?>
            <span class="bs-link-add-info">Didn't receive the code?<a id="resendButton" class="resendButton" href="javascript:void(0);" onclick="resend();">Resend</a></span>
        <?php }else{ ?>
            <span class="bs-link-add-info">Didn't receive the code? Please contact the sender.</span>
            <span class="bs-link-add-info bs-hyperlink-style"><?php echo $ownerEmail; ?></span>
        <?php } ?>
        
        </div>
    </div>
    <input type="hidden" id="docId" value="<?php echo $documentId; ?>" />
    <input type="hidden" id="authType" value="<?php echo $authType; ?>" />
    
</form>
</main>
<script>

var timerOn = true;

<?php if($authType == 1){ ?>
    function resend(){
        $("#accesscodeInput").val('');
        $("#invalid-accesscode-text").hide();
        timerOn = false;
        $("#resendButton").attr("disabled","disabled");
        showLoader("resendButton");

        var documentId = $("#docId").val();
        var rqsturl = "sendDocAccessOtp";
        var postdata = {"documentId":documentId};
        var rqstType = "POST";
        callAjax(rqsturl, postdata, rqstType, function(resp){
            
            hideLoader("resendButton", "Resend");
            $("#resendButton").removeAttr("disabled");

            if(resp.C == 100){

                $('#otp-expires-in').show();
                
                timerOn = true;
                //var remaining = 15*60; //15 minutes
                timer(900);
                var msg = "OTP is sent to your email.";
                var errr = 0;
                showToastMsg(msg, errr);

            }else{
                
                var msg = "Oops! It seems something went wrong. Please try again";
                var errr = 1;
                showToastMsg(msg, errr);
            }

        });
    }
<?php } ?>

    function timer(remaining) {
        var m = Math.floor(remaining / 60);
        var s = remaining % 60;
        
        m = m < 10 ? '0' + m : m;
        s = s < 10 ? '0' + s : s;
        $('#otp-expires-in').html('Expires in '+ m + ':' + s);
        remaining -= 1;
        
        if(remaining >= 0 && timerOn) {
            setTimeout(function() {
                timer(remaining);
            }, 1000);
            return;
        }

        if(!timerOn) {
            // Do validate stuff here
            return;
        }
        
        // Do timeout stuff here
        $("#otp-expires-in").html("OTP expired");
    }


    function reviewDocument(){
        
        $("#invalid-accesscode-text").hide();

        var accesscode = $("#accesscodeInput").val();
        var documentId = $("#docId").val();
        var authType = $("#authType").val();

        if(!isReal(accesscode)){
            
            $("#accesscodeInput").addClass("errorBorder"); 

            var msg = "Please fill the required fields.";
            var errr = 1;
            showToastMsg(msg, errr); 

            $("#accesscodeInput").keyup(function(){
                $("#accesscodeInput").removeClass("errorBorder");
            });

            return false;

        }else{

            $("#reviewButton").attr("disabled","disabled");
            showLoader("reviewButton");
        
            var rqsturl = "verifyaccesscode";
            var postdata = {"authType":authType, "accesscode":accesscode, "documentId":documentId};
            var rqstType = "POST";
            callAjax(rqsturl, postdata, rqstType, function(resp){
                
                if(resp.C != 100){
                    hideLoader("reviewButton", "Review document");
                    $("#reviewButton").removeAttr("disabled");
                }
                
                if(resp.C == 100){
                    var R = resp.R;     
                    var accessToken = R.accessToken;

                    var url = '<?php echo site_url(); ?>/sign?documentId='+documentId+'&t='+accessToken;
                    
                    redirectTo(url);

                }else if(resp.C == 101){
                    
                    $("#invalid-accesscode-text").show();
                    if(authType == 1){
                        var msg = "Invalid OTP.";
                    }else{
                        var msg = "Invalid Access Code.";
                    }
                    
                    var errr = 1;
                    showToastMsg(msg, errr); 

                }else if(resp.C == 102){
                    
                    $("#invalid-accesscode-text").show();
                    if(authType == 1){
                        var msg = "OTP expired.";
                    }else{
                        var msg = "Invalid Access Code.";
                    }
                    
                    var errr = 1;
                    showToastMsg(msg, errr); 

                }else if(resp.C == 103){
                    
                    $("#invalid-accesscode-text").show();
                    if(authType == 1){
                        var msg = "Invalid authentication";
                    }else{
                        var msg = "Invalid authentication";
                    }
                    
                    var errr = 1;
                    showToastMsg(msg, errr); 

                }else{

                    $("#invalid-accesscode-text").show();
                    if(authType == 1){
                        var msg = "Invalid OTP.";
                    }else{
                        var msg = "Invalid Access Code.";
                    }
                    
                    var errr = 1;
                    showToastMsg(msg, errr); 
                }
                
            });
        
        }

    }  

</script>
<?php include("footer.php"); ?>