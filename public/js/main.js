var microBlog = (function(){
    var postBtn;
    var isWideScreen = function(){
        return 800<window.outerWidth;
    }
    
    var isSendMessageBtnLoaded = function(){
         postBtn = document.querySelector('footer input[name="send-msg-form"]');
         return postBtn;
    }
    
    var onPageLoad = function(){
        /* Check width of the window and if user is inside Login-form
         * Set value of post message input appropriately.*/
        if( isWideScreen() && isSendMessageBtnLoaded()){
            postBtn.setAttribute("value","Post Message");
        }else{
            postBtn.setAttribute("value","Post");
        }
    }
    
    var init = function (){
        document.addEventListener("DOMContentLoaded",onPageLoad);
        window.addEventListener("resize",onPageLoad);
    }
    
    return{
       init:init
    }
})();

microBlog.init();
