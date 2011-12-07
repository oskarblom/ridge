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
        
        
        
        window.Ad = Backbone.Model.extend({
            url : '/api/ads'
        });
        
        window.AdCollection = Backbone.Collection.extend({
            model: Ad
        });
        
        window.Ads = new AdCollection;
        
        
        window.TodoView = Backbone.View.extend({
            tagName :  "li",
            //template : _.template($('#ad-template').html()),
            initialize : function(){
              this.model.bind('change', this.render, this);  
            },
            render : function(){
                $(this.el).html(this.template(this.model.toJSON()));
                return this;
            }
        });
        
        window.AppView = Backbone.View.extend({
            el : $('#content'),
            events : {
                'click #add-ad-btn' : 'addAd'
            },
            initialize : function(){
              this.input = this.$("#ad-description");  
            },
            addAd : function(){
                var txt = this.input.val();
                    if(txt) {
                        Ads.create({title : txt})
                    }
                    return false;
            }
        });
        
        window.App = new AppView;
        //Backbone.history.start();
    	
    })
})(jQuery);
