/* eslint-disable func-names, space-before-function-paren, wrap-iife, no-var, quotes, quote-props, prefer-template, comma-dangle, max-len */

import BranchGraph from './branch_graph';
import $ from 'jquery';

export default (function () {
    function Network(opts) {
        var vph;
        $("#filter_ref").click(function () {
            return $(this).closest('form').submit();
        });
        var network = new BranchGraph($(".network-graph"), opts);
        vph = $(window).height() - 250;
        $('.network-graph').css({
            'height': vph + 'px'
        });

        return network;
    }

    return Network;
})();
