/*
    app.js requires:
    
    - jQuery
    - Underscore
    - Backbone
    - JSON2
    - Mustache?
*/

(function($){
	
	function navigate_to(uri){
		
	}
	
	render = function(view, data){
		if(typeof templates[view] == 'undefined') {
			return "";
		}
		return Mustache.to_html(templates[view], data);
	}
	
	ItemModel = Backbone.Model.extend({});
	
	ItemCollection = Backbone.Collection.extend({
		parse: function(response)  {
	  		return response.data;
		}
	});
	
	ItemView = Backbone.View.extend({
		template : 'item',
		tagName: "li",
		events : {
			'click a' : 'go'
		},
		initialize : function(){
			_.bindAll(this, 'render');
			this.model.bind('change', this.render, this);
		},
		render : function(){
			$(this.el).html(render(this.template, this.model.toJSON()));
			return this;
		},
		go : function(){
			event.preventDefault();
			navigate_to(this.model.get('uri'));
		}
		
	});
	
	ItemListView = Backbone.View.extend({
		template : 'list',
		model : 'Items',
		view : ItemView,
		initialize : function(){
			_.bindAll(this, 'render');
			this.collection.bind('reset', this.render);
		},
		render : function(){
			var $items,
			collection = this.collection;
			
			$(this.el).html(render(this.template, {
				model : this.model
			}));
			
			$items = this.$('.items');
			
			var view_class = this.view;
			
			this.collection.each(function(item){
				var current_view = new view_class({
					model : item,
					collection : collection
				});
				$items.append(current_view.render().el);
			});
			return this;
		}
	});
	
	Category = ItemModel.extend({});
	CategoryCollection = ItemCollection.extend({model: ItemModel, url : '/category'});
	category_collection = new CategoryCollection;
	
	CategoryView = ItemView.extend({
		template : 'category'
	});
	
	CategoryListView = ItemListView.extend({
		el : $('#content'),
		model : 'Categorys',
		view : CategoryView
	});

	Ad = ItemModel.extend({});
	AdCollection = ItemCollection.extend({model: Ad, url : '/ad'});
	ad_collection = new AdCollection;
	
	AdView = ItemView.extend({template : 'ad'});
	
	AdListView = ItemListView.extend({
		el : $('#content'),
		model : 'Ads',
		view : AdView
	});
	
	window.AppView = Backbone.Router.extend({
		routes : {
			'' : 'models',
			':model' : 'list',
			':model/:id' : 'view',
			':model/add' : 'add'
		},
		initialize : function(){
			this.adslistview = new AdListView({
				collection : ad_collection
			});
			
			this.categorylistview = new CategoryListView({
				collection : category_collection
			});
		},
		list : function(model){
			switch(model) {
				case 'ad':
					ad_collection.fetch();
					break;
			}
		},
		view : function(model, id){
		
		},
		models : function(){
			category_collection.fetch();
		},
		add : function(model){
			alert("show form for " + model);
		}
	})
	

	
	
	
    $(function(){
        
        window.ridge = new AppView;
		Backbone.history.start({pushState : true});
		
		window.document.addEventListener('click', function(e) {
		    e = e || window.event
		    var target = e.target || e.srcElement
		    if ( target.nodeName.toLowerCase() === 'a' ) {
		        e.preventDefault()
		        var uri = target.getAttribute('href')
		        ridge.navigate(uri, true)
		    }
		});
		window.addEventListener('popstate', function(e) {
			ridge.navigate(location.pathname, true);
		});
    	
    })
})(jQuery);
