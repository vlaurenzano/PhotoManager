$(document).ready(function(){
var registry = {
  ViewServer: new ViewerServer(),
  //ApiServer: new Api('api.php')
}

app( registry );  
})


/** 
 * Main application, run immediately 
 */
function app( registry ){
  
  /**
   * We start off with nothing. This means we'll have to start every page load from scratch
   * until we start storing this and retrieving it
   * @type type
   */
  var token = null;  
  
  /**
   * Initializes the page, checks token if we have token otherwise prompt login
   * @returns {undefined}
   */
  (function initialize(){
    if( token ){
     return; 
    }     
    registry.ViewServer.showLogin();
    
  })(); 
};

/**
 * This isn't really portable since it's tied to all t
 * @param {type} viewPort
 * @returns {ViewerServer}
 */
function ViewerServer( viewPort ){   
  this.showLogin = function(){    
    viewPort.load('partials/login.html');
  }  
  this.showRegister = function(){    
    viewPort.load('partials/register.html');
  }  
}




