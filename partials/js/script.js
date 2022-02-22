// CHANGING LOGIN INPUT METHOD
function inputType(){
    var element = document.getElementById("loginType");

    if ( element.value == "mobile_no") {
        var inputElement = document.getElementById("inputType");
        inputElement.innerHTML = 
            `<input type="text" id="cc" value="+91" maxlength="3" size="4" readonly />
            <input type="tel" maxlength="10" size="12" name="mobileNo" id="mobileNo" required/>`;
            inputElement.classList.add("number");
        } else {
        document.getElementById("inputType").innerHTML = `<input type="email"  required/>`;
    }
}

// REMOVE ALERT
function disableAlert(){
   var alertDiv = document.getElementById("alert");
   alertDiv.remove();
}
