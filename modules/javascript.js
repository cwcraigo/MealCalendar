
function get_recipe(str) {
    
    var xmlhttp; 

    //If empty then hide.
    if (str=="") {
  
        //document.getElementById("txtHint").innerHTML="";
        return;
    }

    //Create new object.
    if (window.XMLHttpRequest){
        
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
        
    } else {
        
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    //Function to print out response    
    xmlhttp.onreadystatechange=function() {
        
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById("popup").innerHTML=xmlhttp.responseText;
            document.getElementById('popup').setAttribute('class','unhide');
            document.getElementById('mealz').setAttribute('class','meals');
        }
    }
    
    //Open object and send it.
    xmlhttp.open("GET","/library/get_recipe.php?q="+str,true);
    xmlhttp.send();

}

function list_recipe(str) {
    
    var xmlhttp; 

    //If empty then hide.
    if (str=="") {
  
        //document.getElementById("txtHint").innerHTML="";
        return;
    }

    //Create new object.
    if (window.XMLHttpRequest){
        
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
        
    } else {
        
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    //Function to print out response    
    xmlhttp.onreadystatechange=function() {
        
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById("popup").innerHTML=xmlhttp.responseText;
            document.getElementById('popup').setAttribute('class','unhide');
            document.getElementById('recipe').setAttribute('class','unhide');
        }
    }
    
    //Open object and send it.
    xmlhttp.open("GET","/library/get_recipe.php?q="+str,true);
    xmlhttp.send();

}

function products(str) {
    
    var xmlhttp;

    //If empty then hide.
    if (str=="") {
  
        document.getElementById("product_suggestions").innerHTML="";
        return;
    }

    //Create new object.
    if (window.XMLHttpRequest){
        
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
        
    } else {
        
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    //Function to print out response    
    xmlhttp.onreadystatechange=function() {
        
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById('p_suggest').setAttribute('class','unhide');
            document.getElementById("product_suggestions").innerHTML=xmlhttp.responseText;
        }
    }
    
    //Open object and send it.
    xmlhttp.open("GET","/library/product_suggestions.php?q="+str,true);
    xmlhttp.send();   
}    

function measurements(str) {
    
    var xmlhttp;

    //If empty then hide.
    if (str=="") {
  
        document.getElementById("product_suggestions").innerHTML="";
        return;
    }

    //Create new object.
    if (window.XMLHttpRequest){
        
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
        
    } else {
        
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    //Function to print out response    
    xmlhttp.onreadystatechange=function() {
        
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById('p_suggest').setAttribute('class','unhide');
            document.getElementById("product_suggestions").innerHTML=xmlhttp.responseText;
        }
    }
    
    //Open object and send it.
    xmlhttp.open("GET","/library/measurement_suggestion.php?q="+str,true);
    xmlhttp.send();   
}

function SubCategory(str) {
    document.getElementById(str).setAttribute('class','unhide');
    document.getElementById(str+'1').setAttribute('onclick','CloseSubCategory(\''+str+'\')');
    document.getElementById(str+'symbol').setAttribute('value','&darr;');
}

function CloseSubCategory(str) {
    document.getElementById(str).setAttribute('class','hidden');
    document.getElementById(str+'1').setAttribute('onclick','SubCategory(\''+str+'\')');
}

function Open(str) {
    document.getElementById(str+'a').setAttribute('class','unhide');
    document.getElementById(str).setAttribute('onclick','Close(\''+str+'\')');
}

function Close(str) {
    document.getElementById(str+'a').setAttribute('class','hidden');
    document.getElementById(str).setAttribute('onclick','Open(\''+str+'\')');
}

function find_inventory(str) {
    
    var xmlhttp; 

    //If empty then hide.
    if (str == "") {
        document.getElementById("recipe_inventory").innerHTML="";
        return;
    }

    //Create new object.
    if (window.XMLHttpRequest){
        
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
        
    } else {
        
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    //Function to print out response    
    xmlhttp.onreadystatechange=function() {
        
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById("recipe_inventory").innerHTML=xmlhttp.responseText;
            document.getElementById('recipe_inventory').setAttribute('class','unhide');
        }
    }
    
    //Open object and send it.
    xmlhttp.open("GET","/library/find_inventory.php?q="+str,true);
    xmlhttp.send();

}
