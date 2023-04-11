function callAjax(rqsturl, postdata, rqstType, cb){

        //rqsturl, postdata, rqstType, cb
        //https://example.com/..., {key:val,key:val,key:val}, GET/POST, calbback

        var requestUrl = SERVICEURL +'/'+ rqsturl;
        var postDataJson = {};
        postDataJson = postdata;

        if(isReal(rqstType) == false){
            rqstType = "GET";
        }

        $.ajax({
            url:requestUrl,
            data:postDataJson,
            dataType:"json",
            type: rqstType,
            tryCount:0,
            retryLimit:3,
            success:function(resp){
                return cb(resp);
            },
            error : function(xhr, textStatus, errorThrown ) {
                if (textStatus == 'timeout') {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        //try again
                        $.ajax(this);
                        return;
                    }
                    return;
                }
                if (xhr.status == 500) {
                    //handle error
                    var resp = {"C":500, "R":"error", "M":"error"};
                    return cb(resp);
                } else {
                    //handle error
                    var resp = {"C":1001, "R":"error", "M":"error"};
                    return cb(resp);
                }
            }
        });

    }

    function callAjaxAsyncFalse(rqsturl, postdata, rqstType, cb){

        //rqsturl, postdata, rqstType, cb
        //https://example.com/..., {key:val,key:val,key:val}, GET/POST, calbback

        var requestUrl = SERVICEURL +'/'+ rqsturl;
        var postDataJson = {};
        postDataJson = postdata;

        if(isReal(rqstType) == false){
            rqstType = "GET";
        }

        $.ajax({
            url:requestUrl,
            data:postDataJson,
            dataType:"json",
            type: rqstType,
            async:false,
            tryCount:0,
            retryLimit:3,
            success:function(resp){
                return cb(resp);
            },
            error : function(xhr, textStatus, errorThrown ) {
                if (textStatus == 'timeout') {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        //try again
                        $.ajax(this);
                        return;
                    }
                    return;
                }
                if (xhr.status == 500) {
                    //handle error
                    var resp = {"C":500, "R":"error", "M":"error"};
                    return cb(resp);
                } else {
                    //handle error
                    var resp = {"C":1001, "R":"error", "M":"error"};
                    return cb(resp);
                }
            }
        });

    }

	function isReal(vl){
		if(vl != "" && vl != undefined && vl != null){
			return true;
		}else{
			return false;
		}
	}
    
	function showToastMsg(msg, err){
		
		if(err == 1){
			//add error class
			//$(".toastMessage span").removeClass("errorMsg");
			//$(".toastMessage span").addClass("successMsg");
            $(".toastMessage .alert").addClass("alert-danger");
			$(".toastMessage .alert").removeClass("alert-success");
		}else{
			//remove error class
			//$(".toastMessage span").removeClass("successMsg");
			//$(".toastMessage span").addClass("errorMsg");
            $(".toastMessage .alert").addClass("alert-success");
			$(".toastMessage .alert").removeClass("alert-danger");
		}
		
		$(".toastMessage .alert").html(msg);
		
		$(".toastMessage").fadeIn(100);
		setTimeout(function(){
			$(".toastMessage").fadeOut(3000);
		}, 3000);
		
	}
	
    /*
	function showError(msg, error){
		if(error == 1){
			$("#alertMessage").addClass("alert-danger");
			$("#alertMessage").removeClass("alert-success");
		}else{
			$("#alertMessage").addClass("alert-success");
			$("#alertMessage").removeClass("alert-danger");
		}
		
		$("#alertMessage").html(msg);
		$("#alertMessage").fadeIn("slow");
		setTimeout(function(){
			$("#alertMessage").fadeOut("slow");
		},2000);
	}
	*/
	
    function showLoader(elmId){
	    var loaderHtml = `<div class="spinner-border text-light" role="status"><span class="sr-only">Loading...</span></div>`; var loaderHtml = `<div class="spinner-border text-light" role="status"></div>`;
	    $("#"+elmId).html(loaderHtml);
    }
    
    function hideLoader(elmId, content){
	    $("#"+elmId).html(content);
	}
	
	function validateEmail(email){
	 return email.match(
	 /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
	 );
	}

    function currentDateTime(){
        var currentdate = new Date(); 
        var datetime = "Last Sync: " + currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
        
        var result = {"dateTime":datetime, "currentdate":currentdate};
        return result;
    }

    function redirectTo(url){
        window.location.href = url;
    }