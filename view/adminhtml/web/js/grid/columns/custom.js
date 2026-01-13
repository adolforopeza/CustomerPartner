define([
    'underscore',
    'Magento_Ui/js/grid/columns/select'
], function (_, Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Aoropeza_CustomerPartner/ui/grid/cells/option'
        },
        getOrderStatusColor: function (row) {
            let status_cell = ["grid-severity-minor", "grid-severity-notice", "grid-severity-notice", "grid-severity-critical", "grid-severity-minor"][row.is_active];
            return row.status_flow === "2" ? "grid-severity-notice" : status_cell;
        },
        getLabel: function (row) {
            return ["OFF", "ON"][row.is_active];
        }
    });
});
