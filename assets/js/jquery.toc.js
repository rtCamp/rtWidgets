/*!
 * toc - jQuery Table of Contents Plugin
 * v0.1.2
 * http://projects.jga.me/toc/
 * copyright Greg Allen 2013
 * MIT License
 */
(function($) {
    $.fn.toc = function(options) {
        var self = this;
        var opts = $.extend({}, jQuery.fn.toc.defaults, options);

        var container = $(opts.container);
        var headings = $(opts.selectors, container);
        var headingOffsets = [];
        var activeClassName = 'rtw-toc-active';

        var scrollTo = function(e) {
            if (opts.smoothScrolling) {
                e.preventDefault();
                var elScrollTo = $(e.target).attr('href');
                var $el = $(elScrollTo);

                $('body,html').animate({scrollTop: $el.offset().top}, 400, 'swing', function() {
                    location.hash = elScrollTo;
                });
            }
            $('a', self).removeClass(activeClassName);
            $(e.target).addClass(activeClassName);
        };

        //highlight on scroll
        var timeout;
        var highlightOnScroll = function(e) {
            if (timeout) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(function() {
                var top = $(window).scrollTop(),
                        highlighted;
                for (var i = 0, c = headingOffsets.length; i < c; i++) {
                    if (headingOffsets[i] >= top) {
                        $('a', self).removeClass(activeClassName);
                        highlighted = $('a:eq(' + (i - 1) + ')', self).addClass(activeClassName);
                        opts.onHighlight(highlighted);
                        break;
                    }
                }
            }, 50);
        };
        if (opts.highlightOnScroll) {
            $(window).bind('scroll', highlightOnScroll);
            highlightOnScroll();
        }

        return this.each(function() {
            //build TOC
            var el = $(this);
            var listParent = '';
            if ( opts.listStyle === 'ol' || opts.listStyle === 'ul' ) {
                listParent = $('<' + opts.listStyle + '/>');
            } else {
                listParent = $('<div/>');
            }
            headings.each(function(i, heading) {
                var $h = $(heading);
                headingOffsets.push($h.offset().top - opts.highlightOffset);

                //add anchor
                var anchor = $('<span/>').attr('id', opts.anchorName(i, heading, opts.prefix)).insertBefore($h);

                //build TOC item
                var a = $('<a/>')
                        .text(opts.headerText(i, heading, $h))
                        .attr('href', '#' + opts.anchorName(i, heading, opts.prefix))
                        .bind('click', function(e) {
                            scrollTo(e);
                            el.trigger('selected', $(this).attr('href'));
                        });

                var li = $('<li/>')
                        .addClass(opts.itemClass(i, heading, $h, opts.prefix))
                        .append(a);
                if ( opts.listStyle === 'ol' || opts.listStyle === 'ul' ) {
                    listParent.append(li);
                } else {
                    listParent.append(a);
                }
            });
            el.html(listParent);
        });
    };

    jQuery.fn.toc.defaults = {
        container: '#content',
        selectors: 'h1,h2,h3,h4,h5',
        smoothScrolling: true,
        prefix: 'toc',
        listStyle: 'ul', // ul, ol
        onHighlight: function() {},
        highlightOnScroll: true,
        highlightOffset: 100,
        anchorName: function(i, heading, prefix) {
            return prefix + i;
        },
        headerText: function(i, heading, $heading) {
//            var test = $heading.text().replace('/ /g', '-');
//            console.log( test );
            return $heading.text();
        },
        itemClass: function(i, heading, $heading, prefix) {
            return prefix + '-' + $heading[0].tagName.toLowerCase();
        }
    };
})(jQuery);