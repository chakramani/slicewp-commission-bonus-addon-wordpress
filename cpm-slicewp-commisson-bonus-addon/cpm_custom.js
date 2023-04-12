jQuery(document).ready(function () {
    // jQuery('#addRow').on('click', function (e) {
    //     let lastRow = jQuery('.row:last').html();
    //     console.log(lastRow);
    //     var matchedString = lastRow.match(/<div class="count">([0-9]*)<\/div>/);
    //     var matchedInputField1 = lastRow.match(/value="([0-9]*)">/);
    //     console.log(matchedInputField1);

    //     if (matchedString.length) {
    //         let nextNumber = matchedString[1] * 1;
    //         nextNumber++
    //         var newRow = lastRow.replace(matchedString[0], '<div class="count">' + nextNumber + '</div>');
    //     }

    //     jQuery('#inputs').append('<div class="container row">' + newRow + '</div>');
    //     return false;
    // });
    jQuery(document).on('click','#addRow' , function (e) {
        let lastRow = jQuery('.cpm_row:last').html();
        var matchedString = lastRow.match(/<div class="count">([0-9]*)<\/div>/);
        var matchedInputField1 = lastRow.match(/value="([0-9]*)"/);
        var matchedInputField2 = lastRow.match(/class="bonus" value="([0-9]*)"/);
        
        // console.log(matchedInputField1);
        // console.log(matchedInputField2);
        if (matchedString.length) {
            let nextNumber = matchedString[1] * 1;
            nextNumber++
            var newRow = lastRow.replace(matchedString[0], '<div class="count">' + nextNumber + '</div>');
        }
        
        if(matchedInputField1.length){
            matchedInputField1[1] = '';
            newRow = newRow.replace(matchedInputField1[0],'value="' +matchedInputField1[1]+'"');
        }
        if(matchedInputField2.length){
            matchedInputField2[1] = '';
            newRow = newRow.replace(matchedInputField2[0],'class="bonus" value="' +matchedInputField2[1]+'"');
        }

        jQuery('#cpm-slicewp-commission-table').append('<tr class="row cpm_row" id="cpm_row">' + newRow + '</tr>');
        return false;
    });
    jQuery(document).on('click' ,'#remove' , function(){
        jQuery(this).parent().remove();
        return false;
    });
});
