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
    	
    	App.bind('added', function(ad){
            console.log(ad);
    	})
    	
    	/* A Collection */
    	AdList = Backbone.Collection.extend({
    	    model : App
    	    // localStorage : new Store('ads')
    	});
    	
    	ads = new AdList;
    	
    	/* The View */
    	AdView = Backbone.View.extend({
    	    initialize : function(){
    	        this.render();
    	    },
    	    render : function(){
    	        var template = _.template($('#ad_template').html(), {});
    	        this.el.html(template);
    	    },
    	    events: {
    	        "click input[type=submit]" : "addAd",
    	        "change #ad_rent_type" : 'massageUI'
    	    },
    	    addAd : function(event){
    	        alert("add ad");
    	        return false;
    	    },
    	    massageUI : function(event){
    	        console.log(event);
    	    }
    	});
    	
    	var ad_view = new AdView({el : $('#ad_container')})
    	
    	var AppController = Backbone.Controller.extend({
    	    routes : {
    	        '' : 'index',
    	        '/ad' : 'list_ads',
    	        '/ad/:id' : 'show_ad'
    	    },
    	    index : function(){
    	        alert("show home page");
    	    },
    	    list_ads : function(){
    	        alert("show ads page");
    	    },
    	    show_ad : function(ad_id){
    	        alert("show ad with id:" + ad_id);
    	    }
    	    
    	})
    	
    	// var ApplicationController = new AppController;   
    	// 
        Backbone.history.start({pushState: true});
    	
    })
})(jQuery);
