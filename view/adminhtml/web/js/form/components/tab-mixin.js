define([
    'jquery'
], function (
    $
) {
    'use strict';

    return function (target) {
        return target.extend({
            /**
             * Toogle base on tab
             */
            activate: function () {
                var self = this;
                this._super();
                $('.change-customer-pwd').toggle(self.dataScope == 'customer_edit_tab_view_content');
            }
        });
    };
});
