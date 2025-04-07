import $ from 'jquery';
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

// @todo: execute this function only
new AjaxRequest(TYPO3.settings.ajaxUrls.mask2blocks_prepare)
    .get()
    .then(async function (response) {
        const resolved = await response.resolve();
        console.log(resolved.result);
    });

$(document).ready(function() {
    // Process (1st step)
    $('#mask2blocks-migration-form').on('submit', function(e){
        e.preventDefault();
        
        const action = $(this).prop('action');
        const data = {
            maskuids: []
        };
        $('.mask-element-check:checked').each(function(){
            data.maskuids.push($(this).val());
        });

        if(data.maskuids.length) {
            $('.err-messages').addClass('d-none');
            $('.steps-overlay').animate({'width': '33%'}, 6000, function(){
                $('.steps').text('Step 2/3');
            })
            ajax(action, data);
        }else{
            $('.err-messages').removeClass('d-none').text('Please select items.');  
        }
    })
});

function ajax(url, data, method = 'POST') {
    $.ajax({
        url : url,
        type : method,
        data : data,
        // dataType:'json',
        success : function(data) {
            $('#mask-be-module-container').html(data)
        }
    });
}
