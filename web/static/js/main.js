jQuery.fn.center = function () {
    this.css('position', 'absolute');
    this.css('top', (this.parent().height() - this.height()) / 2 + 'px');
    this.css('left', (this.parent().width() - this.width()) / 2 + 'px');
    return this;
};

jQuery.fn.ajaxMask = function () {
    this.children('*').fadeTo(0, 0.2);
    this.prepend($('<img/>').attr('src', 'static/img/ajax.gif'));
    this.children('img').center();
    return this;
};

