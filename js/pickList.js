(function ($) {

   $.fn.pickList = function (options) {

      var opts = $.extend({}, $.fn.pickList.defaults, options);

      this.fill = function () {
         var option = '';
			
         $.each(opts.data, function (key, val) {
			
            option += '<option value= ' + val.id + ' id=' + val.id + ' selected>' + val.text + '</option>';
			
         });
         this.find('.pickData').append(option);
      };
      this.controll = function () {
         var pickThis = this;

         pickThis.find(".pAdd").on('click', function () {
            var p = pickThis.find(".pickData option:selected");
            p.clone().appendTo(pickThis.find(".pickListResult"));
            p.remove();
         });

         pickThis.find(".pAddAll").on('click', function () {
            var p = pickThis.find(".pickData option");
            p.clone().appendTo(pickThis.find(".pickListResult"));
            p.remove();
         });

         pickThis.find(".pRemove").on('click', function () {
            var p = pickThis.find(".pickListResult option:selected");
            p.clone().appendTo(pickThis.find(".pickData"));
            p.remove();
         });

         pickThis.find(".pRemoveAll").on('click', function () {
            var p = pickThis.find(".pickListResult option");
            p.clone().appendTo(pickThis.find(".pickData"));
            p.remove();
         });
      };

      this.getValues = function () {
         var objResult = [];
         this.find(".pickListResult option").each(function () {
            objResult.push({
               id: $(this).data('id'),
               text: this.text
            });
         });
         return objResult;
      };

      this.init = function () {
         var pickListHtml =
                 "<div class='row'>" +
                 "  <div class='col-sm-5 col-xs-12 un'>" +
                 "	 <select class='form-control pickListSelect pickData' id='mySelectBox'  multiple></select>" +
                 " </div>" +
                 " <div class='col-sm-2 col-xs-12 pickListButtons'>" +
                 "	<a class='pAdd btn btn-default btn-warning text-center'>" + opts.add + "</a>" +
                 "      <a  class='pAddAll btn btn-default btn-warning text-center'>" + opts.addAll + "</a>" +
                 "	<a  class='pRemove btn btn-default btn-warning text-center'>" + opts.remove + "</a>" +
                 "	<a  class='pRemoveAll btn btn-default btn-warning text-center'>" + opts.removeAll + "</a>" +
                 " </div>" +
                 " <div class='col-sm-5 col-xs-12'>" +
                 "    <select class='form-control pickListSelect pickListResult'   name='projectname[]' multiple></select>" +
                 " </div>" +
                 "</div>";

         this.append(pickListHtml);

         this.fill();
         this.controll();
      };

      this.init();
      return this;
   };

   $.fn.pickList.defaults = {
      add: 'Add',
      addAll: 'Add All',
      remove: 'Remove',
      removeAll: 'Remove All'
   };


}(jQuery));
