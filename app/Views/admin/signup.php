<?php include("header.php"); ?>
<div class="container mt-5">
	<div class="row mb-4">
		<span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>" /></span>
	</div>
</div>
<div class="signInUpFormContainer container mt-5">
  <!-- Pills navs -->
  <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link tab-login" id="tab-login" data-mdb-toggle="pill" role="tab"
        aria-controls="pills-login" aria-selected="true" href="<?php echo site_url("signin"); ?>">Login</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link active tab-register" id="tab-register" data-mdb-toggle="pill" role="tab"
        aria-controls="pills-register" aria-selected="false" href="<?php echo site_url("signup"); ?>">Register</a>
    </li>
  </ul>
  <!-- Pills navs -->

  <!-- Pills content -->
  <div class="tab-content">
    <div class="tab-pane fade show active pills-register" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
      <form action="javascript:void(0);">
        
        <!-- Name input -->
        <div class="form-outline mb-4">
          <input type="text" id="registerFName" class="form-control" onfocus="setPlaceholder('registerFNamePlaceholder');" onfocusout="resetPlaceholder('registerFNamePlaceholder');"/>
          <label class="form-label inputPlaceholderPosition inputPlaceholderAnimateCenter" id="registerFNamePlaceholder" for="registerFName">First Name</label>
        </div>

        <!-- Username input -->
        <div class="form-outline mb-4">
          <input type="text" id="registerLName" class="form-control" onfocus="setPlaceholder('registerLNamePlaceholder');" onfocusout="resetPlaceholder('registerLNamePlaceholder');"/>
          <label class="form-label inputPlaceholderPosition inputPlaceholderAnimateCenter" id="registerLNamePlaceholder" for="registerLName">Last Name</label>
        </div>

        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" id="registerEmail" class="form-control" onfocus="setPlaceholder('registerEmailPlaceholder');" onfocusout="resetPlaceholder('registerEmailPlaceholder');"/>
          <label class="form-label inputPlaceholderPosition inputPlaceholderAnimateCenter" id="registerEmailPlaceholder" for="registerEmail">Email</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="registerPassword" class="form-control" onfocus="setPlaceholder('registerPasswordPlaceholder');" onfocusout="resetPlaceholder('registerPasswordPlaceholder');"/>
          <label class="form-label inputPlaceholderPosition inputPlaceholderAnimateCenter" id="registerPasswordPlaceholder" for="registerPassword">Password</label>
        </div>

        <!-- Repeat Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="registerRepeatPassword" class="form-control" onfocus="setPlaceholder('registerRepeatPasswordPlaceholder');" onfocusout="resetPlaceholder('registerRepeatPasswordPlaceholder');"/>
          <label class="form-label inputPlaceholderPosition inputPlaceholderAnimateCenter" id="registerRepeatPasswordPlaceholder" for="registerRepeatPassword">Repeat password</label>
        </div>

        <!-- Checkbox -->
      <!--
        <div class="form-check d-flex justify-content-center mb-4">
          <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck" checked
            aria-describedby="registerCheckHelpText" />
          <label class="form-check-label" for="registerCheck">
            I have read and agree to the terms
          </label>
        </div>
      -->
        <!-- Submit button -->
        <div class="form-outline mb-4">
          <button type="submit" class="btn btn-primary btn-block mb-3" onclick="signUp();">Sign Up</button>
        </div>
      </form>
    </div>
  </div>
  <!-- Pills content -->
</div>
<script>

  
	function setPlaceholder(elmId){
		$("#"+elmId).addClass("inputPlaceholderAnimateTop",125);
		$("#"+elmId).removeClass("inputPlaceholderAnimateCenter",125);		
	}

	function  resetPlaceholder(elmId){
    
		var forAttr = $("#"+elmId).attr("for");
		
		if(!isReal($("#"+forAttr).val())){
		  $("#"+elmId).addClass("inputPlaceholderAnimateCenter",125);
		  $("#"+elmId).removeClass("inputPlaceholderAnimateTop",125);
    }
	}

	function signUp(){
		
		var registerFName = $("#registerFName").val();
		var registerLName = $("#registerLName").val();
		//var registerIsd = $("#registerIsd").val();
		//var registerPhone = $("#registerPhone").val();
		var registerEmail = $("#registerEmail").val();
		var registerPassword = $("#registerPassword").val();
		var registerRepeatPassword = $("#registerRepeatPassword").val();

		if(isReal(registerFName) == false){
			var err = 1;
			showToastMsg("Please enter the First Name.", err);
		}else if(isReal(registerLName) == false){
			var err = 1;
			showToastMsg("Please enter the Last Name.", err);
		}else if(isReal(registerEmail) == false){
			var err = 1;
			showToastMsg("Please enter the Email.", err);
		}else if(validateEmail(registerEmail) == false){
			var err = 1;
			showToastMsg("Please enter the valid Email.", err);
		}else if(isReal(registerPassword) == false){
			var err = 1;
			showToastMsg("Please enter the Password.", err);
		}else if(isReal(registerRepeatPassword) == false){
			var err = 1;
			showToastMsg("Please enter the Repeat Password.", err);
		}else if(registerPassword != registerRepeatPassword){
			var err = 1;
			showToastMsg("Password does not match with Repeat-Password.", err);
		}else{
			
			var rqsturl = "signup";
			var postdata = {"registerFName":registerFName, "registerLName":registerLName, "registerEmail":registerEmail, "registerPassword":registerPassword, "registerRepeatPassword":registerRepeatPassword};
			var rqstType = "POST";
			
			callAjax(rqsturl, postdata, rqstType, function(resp){
				if(resp.C == 100){
					window.location.href = "<?php echo base_url('verify'); ?>";
				}else{
					var err = 1;
					showToastMsg("Something went wrong! please try again.", err);	
				}
			});	
		}

	}
</script>

<?php include("footer.php"); ?>