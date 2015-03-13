var microBlog = (function(){
    var postBtn;
    var signinForm;
    var isWideScreen = function(){
        return 800<window.outerWidth;
    };
    
    var isSendMessageBtnLoaded = function(){
         postBtn = document.querySelector('footer input[name="send-msg-form"]');
         return (null !== postBtn);
    };
    
    var isSignInFormLoaded = function(){
        signinForm = document.querySelector('form[name="signin-form"]');
        return (null !== signinForm);
    };
    
    var onPageLoad = function(){
        /* Check width of the window and if user is inside Login-form
         * Set value of post message input appropriately.*/
        if(isSendMessageBtnLoaded()){
            if( isWideScreen()){
                postBtn.setAttribute("value","Post Message");
            }else{
                postBtn.setAttribute("value","Post");
            }
        }
        
        if(isSignInFormLoaded()){
            var formBoxArray = signinForm.querySelectorAll(".col-3-sign-in");
            console.log(formBoxArray);
            if( isWideScreen()){
                signinForm.classList.add("vertical-alignment");
                for(var i=0; i<formBoxArray.length; i++){
                    formBoxArray[i].classList.add("vertical-alignment");
                }
                
            }else{
                signinForm.classList.remove("vertical-alignment");
                for(var i=0; i<formBoxArray.length; i++){
                    formBoxArray[i].classList.remove("vertical-alignment");
                }                
            }
        }
    };
    
    var init = function (){
        document.addEventListener("DOMContentLoaded",onPageLoad);
        window.addEventListener("resize",onPageLoad);
    };
    
    return{
       init:init
    }
})();

microBlog.init();
