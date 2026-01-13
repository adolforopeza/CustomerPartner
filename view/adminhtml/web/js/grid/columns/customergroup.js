define([
    'underscore',
    'Magento_Ui/js/grid/columns/select'
], function (_, Column) {
    'use strict';
    return Column.extend({
        defaults: {
        },
        getLabel: function (row) {
            var groupId = row[this.index],
                option;

            option = _.find(this.options, function (opt) {
                return String(opt.value) === String(groupId);
            });

            return option && option.label ? option.label : groupId;
        }
    });
});
