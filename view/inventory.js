
$(document).ready(function(){
 
    var i = $('input').size() - 2;
 
    $('#add').click(function() {
        $('<div><input type="text" class="field" name="dynamic'+i+'" /></div>').fadeIn('slow').appendTo('.inputs');
        i++;
        document.getElementById('field_num').setAttribute('value', $('input').size() - 2);
    });
 
    $('#remove').click(function() {
        if(i > 1) {
            $('.field:last').remove();
            i--;
            document.getElementById('field_num').setAttribute('value', $('input').size() - 2);
        }
    });
 
    $('#reset').click(function() {
        while(i > 2) {
            $('.field:last').remove();
            i--;
            document.getElementById('field_num').setAttribute('value', $('input').size() - 2);
        }
    });
 
    var j = $('input').size() - 6;
    
    $('#remove_add').click(function() {
        if(j > 1) {
            $('.field:last').remove();
            j--;
            document.getElementById('field_num').setAttribute('value', $('input').size() - 6);
        }
    });
 
    $('#add_add').click(function() {
        $('<div class="field" ><input type="text" id="quantity'+j+'" name="quantity'+j+'" placeholder="Quantity" size="10" /> | <input type="text" id="measurement'+j+'" name="measurement'+j+'" placeholder="Measurment Name or Symbol" size="15" onkeyup="measurements(this.value)" /> | <input type="text" id="product'+j+'" name="product'+j+'" placeholder="Product Name" size="15" onkeyup="products(this.value)" /> <br /></div>').fadeIn('slow').appendTo('.inputs');
        j++;
        document.getElementById('field_num').setAttribute('value', $('input').size() - 6);
    });

});
