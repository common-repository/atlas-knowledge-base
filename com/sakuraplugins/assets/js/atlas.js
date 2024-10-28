(function() {
    "use strict";
    function ATLASRatingManager() {
        this.currentRating;
        this.articleId;
        this.states = ['disappointed', 'neutral', 'happy'];
        this.selectItem = function(rating) {
            if (rating) {
                for (let i = 0; i < this.states.length; i++) {
                    jQuery('#' + this.states[i] + '-off').css('display', 'block');
                    jQuery('#' + this.states[i] + '-on').css('display', 'none');

                    if (rating === this.states[i]) {
                        jQuery('#' + this.states[i] + '-off').css('display', 'none');
                        jQuery('#' + this.states[i] + '-on').css('display', 'block');
                    }
                }
            }
        }
        this.setRating = function(rating) {
            var data = {
                'action': 'atlas_set_rating',
                'postId': this.articleId,
                'rating': rating
            };
            var _self = this;
            _self.selectItem(rating);
            jQuery.post(window.atlas_ajax_object.AJAX_URL, data, function(response) {
                var r = JSON.parse(response);
                if (r.status === 'OK') {
                    _self.selectItem(r.rating);
                }
            });
        }
        this.getRating = function() {
            var data = {
                'action': 'atlas_get_rating',
                'postId': this.articleId,
            };
            var _self = this;
            jQuery.post(window.atlas_ajax_object.AJAX_URL, data, function(response) {
                var r = JSON.parse(response);
                if (r.status === 'OK') {
                    _self.selectItem(r.rating);
                }
            });
        }
        this.init = function() {
            this.articleId = jQuery('#atlas-rating').attr('data-articleid');
            var _self = this;
            this.disappointed = jQuery('#disappointed');
            this.disappointed.on('click', function(e) {
                e.preventDefault();
                _self.setRating('disappointed');
            });
            
            this.neutral = jQuery('#neutral');
            this.neutral.on('click', function(e) {
                e.preventDefault();
                _self.setRating('neutral');
            });
            
            this.happy = jQuery('#happy');
            this.happy.on('click', function(e) {
                e.preventDefault();
                _self.setRating('happy');
            });
            this.getRating();
        }
    }
    jQuery(document).ready(function() {
        var atsManager = new ATLASRatingManager();
        atsManager.init();
    });
  })();