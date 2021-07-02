(function ($) {
    popModals = {

        //-------------------------------------------------
        // PUBLIC FUNCTIONS
        //-------------------------------------------------

        modalForm : function (args) {

            var t = this;
            var pageSection = args.pageSection, targets = args.targets;

            targets.on(
                'show.bs.modal',
                function (e) {

                    var modal = $(this);
                    var link = $(e.relatedTarget);

                    // Close the feedback message
                    var blocks = $(modal.data('initjs-targets'));
                    blocks.each(
                        function () {
                            var block = $(this);
                            popManager.closeMessageFeedback(block);
                        }
                    )
                }
            );
        }
    };
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popModals, ['modalForm']);