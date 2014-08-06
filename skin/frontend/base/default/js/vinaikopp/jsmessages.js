
var JsMessages = Class.create();
JsMessages.prototype = {
    initialize: function(data) {
        this.domain = data.domain;
        this.cookie = data.cookie;
        this.wrapper = '#vinaikopp-jsmessages';
        this.messages = decodeURIComponent(Mage.Cookies.get(this.cookie));
        if (this.messages && this.messages !== '-') {
            var cookie_name = this.cookie;
            [this.domain, '.' + this.domain].each(function(domain) {
                Mage.Cookies.set(cookie_name, '-', Mage.Cookies.expires, Mage.Cookies.path, domain);
            });
            document.observe('dom:loaded', this.renderMessages.bind(this));
        }
    },
    renderMessages: function()
    {
        var type, messages = JSON.parse(this.messages);

        for(type in messages) {
            if (messages.hasOwnProperty(type) && messages[type].length) {
                this.renderMessage(type, messages[type]);
            }
        }
    },
    renderMessage: function(type, messages)
    {
        var container, content = '';
        if (container = this.getMessagesElement(type)) {
            messages.each(function(message) {
                content += "<li>" + decodeURIComponent(message) + "</li>\n";
            });
            container.update("<ul>\n" + content + "</ul>")
            container.show();
        }
    },
    getMessagesElement: function(type)
    {
        var elements = $$(this.wrapper + ' .' + type + '-msg');
        if (elements.length) {
            return elements[0];
        }
        return false;
    }
}
