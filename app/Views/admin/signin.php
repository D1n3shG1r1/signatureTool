<?php include("header.php"); ?>

<div class="container mt-5">
	<div class="row mb-4">
		<span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>" /></span>
	</div>
</div>


<!-- Pills navs -->
<div class="signInUpFormContainer container mt-5">
	<ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
	<li class="nav-item" role="presentation">
		<a class="nav-link active tab-login" id="tab-login" data-mdb-toggle="pill" role="tab"
		aria-controls="pills-login" aria-selected="true" href="<?php echo base_url("signin"); ?>">Login</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link tab-register" id="tab-register" data-mdb-toggle="pill" role="tab"
		aria-controls="pills-register" aria-selected="false" href="<?php echo base_url("signup"); ?>" >Register</a>
	</li>
	</ul>
	<!-- Pills navs -->

	<!-- Pills content -->
	<div class="tab-content">
	<div class="tab-pane fade show active pills-login" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
		<form action="javascript:void(0);">
		
		<!-- Email input -->
		<div class="form-outline mb-4">
			<input type="email" id="loginEmail" class="form-control" onfocus="setPlaceholder('loginEmailPlaceholder');" onfocusout="resetPlaceholder('loginEmailPlaceholder');"/>
			<label id="loginEmailPlaceholder" class="form-label inputPlaceholderPosition inputPlaceholderAnimateCenter" for="loginEmail">Email address</label>
		</div>
		
		<!-- Password input -->
		<div class="form-outline mb-4">
			<input type="password" id="loginPassword" class="form-control" onfocus="setPlaceholder('loginPasswordPlaceholder');" onfocusout="resetPlaceholder('loginPasswordPlaceholder');"/>
			<label id="loginPasswordPlaceholder" class="form-label inputPlaceholderPosition inputPlaceholderAnimateCenter" for="loginPassword">Password</label>
		</div>

		<!-- 2 column grid layout -->
		<!--
		<div class="row mb-4">
			<div class="col-md-6 d-flex justify-content-center">
			
			<div class="form-check mb-3 mb-md-0">
				<input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
				<label class="form-check-label" for="loginCheck"> Remember me </label>
			</div>
			</div>

			<div class="col-md-6 d-flex justify-content-center">
			
			<a href="#!">Forgot password?</a>
			</div>
		</div>
		-->

		<!-- Submit button -->
		<div class="form-outline mb-4">
			<button type="submit" class="btn btn-primary btn-block mb-4" onclick="signIn();">Sign in</button>
		</div>

		<!-- Register buttons -->
		<div class="text-center">
			<p>Not a member? <a href="<?php echo base_url("signup"); ?>">Register</a></p>
		</div>
		</form>
	</div>
	</div>
	<!-- Pills content -->
</div>
<script>
	var accountVerification = "<?php echo $accountVerification; ?>";

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

	function signIn(){
		
		var email = $("#loginEmail").val();
		var password = $("#loginPassword").val();
		
		if(isReal(email) == false){
			var err = 1;
			showToastMsg("Please enter the email", err);	
			return false;
		}else if(validateEmail(email) == false){
			var err = 1;
			showToastMsg("Please enter the valid email", err);	
			return false;
		}else if(isReal(password) == false){
			var err = 1;
			showToastMsg("Please enter the password", err);	
			return false;
		}else{
			
			if(parseInt(accountVerification) == 1){
				//account verification
				var rqsturl = "verify";	
			}else{
				//signin
				var rqsturl = "signin";	
			}
			
			
			var postdata = {"email":email, "password":password};
			var rqstType = "POST";
			
			callAjax(rqsturl, postdata, rqstType, function(resp){
				if(resp.C == 100){
					window.location.href = "<?php echo site_url('dashboard'); ?>";
				}else if(resp.C == 102){
					var err = 1;
					showToastMsg("Un-Verified Account.", err);	
				}else{
					var err = 1;
					showToastMsg("Entered invalid email and password.", err);	
				}
			});	
		}
		
	}
</script>
<?php include("footer.php"); ?>