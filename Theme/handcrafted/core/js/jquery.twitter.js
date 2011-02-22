
// Twitter Relative Time
// http://twitter.com/javascripts/blogger.js

function relative_time(time_value) {
	var values = time_value.split(" ");
	time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
	var parsed_date = Date.parse(time_value);
	var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
	var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
	delta = delta + (relative_to.getTimezoneOffset() * 60);

	if (delta < 60) {
		return 'less than a minute ago';
	} else if(delta < 120) {
		return 'about a minute ago';
	} else if(delta < (60*60)) {
		return (parseInt(delta / 60)).toString() + ' minutes ago';
	} else if(delta < (120*60)) {
		return 'about an hour ago';
	} else if(delta < (24*60*60)) {
		return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
	} else if(delta < (48*60*60)) {
		return '1 day ago';
	} else {
		return (parseInt(delta / 86400)).toString() + ' days ago';
	}
}

(function($){
	$.fn.tweets = function(options) {
		$.ajaxSetup({
			cache: true
		});
		var defaults = {
			tweets: 1,
			target: null
		};
		var options = $.extend(defaults, options);

		return this.each(function() {

			var obj = $(this);

			$.getJSON('http://twitter.com/statuses/user_timeline/' + options.username +'.json?count=' + options.tweets +'&callback=?', function(data) {

				var tweets = [];

				$.each(data, function(i, tweet) {
					if( tweet.text !== undefined ) {
						tweet.text = tweet.text
						.replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,'<a target="_blank" href="$&">$&</a> ')
						.replace(/@(.*?)(,?)(\s|\(|\)|$)/g,'<a target="_blank" href="http://twitter.com/$1">@$1</a>$2$3');
						tweets.push(tweet.text);
					}
				});

				for (var i in tweets) {
					var tweet = tweets[i];
					var matches = tweet.match(/#(.*?)(\s|$)/g);
					for (var j in matches) {
						var match = $.trim(matches[j]);
						var link = '<a target="_blank" href="http://twitter.com/#!/search?q=%23' + match.replace('#','') + '">' + match + '</a>';
						var regex = new RegExp(match, 'g');
						tweets[i] = tweet.replace(regex, link);
					}
				}

				

				options.callback(tweets, obj);
			});
		});
	};
})(jQuery);