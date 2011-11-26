/*
    app.js requires:
    
    - jQuery
    - Underscore
    - Backbone
    - JSON2
    - Mustache?
*/

(function($){
    $(function(){
        
        /* The Model */
    	App = Backbone.Model.extend({
    	    defaults : function(){
    	        return {
    	            foo : 'bar'
    	        }
    	    }
    	});
    	
    	/* A Collection */
    	AdList = Backbone.Collection.extend({
    	    model : App,
    	    localStorage : new Store('ads')
    	});
    	
    	ads = new AdList;
    	
    	/* The View */
    	AdView = Backbone.View.extend({
    	    tagName : "li",
    	    template : _.template($('#ad-template').html()),
    	    events : {
    	        "click .reply" : "reply"
    	    },
    	    reply : function(){
    	        //Reply
    	    }
    	});
    	
    })
})(jQuery);
