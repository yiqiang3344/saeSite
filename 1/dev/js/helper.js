// Generated by CoffeeScript 1.6.3
(function() {
  window.oFinalPage = Class.extend({
    init: function(oArgs) {
      this.oArgs = oArgs;
      return this.__fInitData();
    },
    __fInitData: function() {},
    fPrint: function() {
      document.write('<div class="maincontent"></div>');
      return this.fRefresh();
    },
    fRefresh: function(bRefresh) {
      bRefresh = bRefresh != null ? bRefresh : false;
      $('.maincontent').html(this.oArgs.oTemplate.render(this.oArgs.oParams, this.oArgs.oPartials));
      return this.fBind();
    },
    fBind: function() {
      return $('.js_back').click(function() {
        return State.back(1);
      });
    }
  });

}).call(this);
