(function ($) {
    if (typeof WCE_Order_Statuses !== 'undefined' && WCE_Order_Statuses.length === 0) {
        return;
    }

    let title_text = '',
        $columnStatus,
        $selectOption;
    for (let key in WCE_Order_Statuses) {
        $selectOption = $('<option>').val('mark_' + key).text(WCE_Order_Statuses[key].bulk_action);
        $selectOption.appendTo("select[name='action'], select[name='action2']");
        $columnStatus = $('.column-order_status .status-' + key);
        title_text = $columnStatus.html();
        $columnStatus.attr('alt', title_text);
        $columnStatus.append(WCE_Order_Statuses[key].icon);
        $columnStatus.css('text-indent', '0');
    }


})(jQuery);