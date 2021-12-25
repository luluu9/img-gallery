function get_cookies_array() {
    var cookies = { };
    if (document.cookie) {
        var split = document.cookie.split(';');
        for (var i = 0; i < split.length; i++) {
            var name_value = split[i].split("=");
            name_value[0] = $.trim(name_value[0]);
            cookies[decodeURIComponent(name_value[0])] = decodeURIComponent(name_value[1]);
        }   
    }
    return cookies;
}

var cookies = get_cookies_array();
var favourities = Object.values(cookies);
var checkboxes = document.querySelectorAll(".rememberCheckbox");

checkboxes.forEach(function(checkbox) {
    if (favourities.includes(checkbox.value)) {
        checkbox.checked = true;
    }
    checkbox.addEventListener('change', function() {
    if (checkbox.checked) {
        var serializedData = {'name': 'id[', 'value': checkbox.value};
        request = $.ajax({
            url: "/setcookie.php",
            type: "post",
            data: serializedData
        });
        request.done(function (response, textStatus, jqXHR){
            console.log(response);
        });
    }
    else {
        var serializedData = {'name': 'id', 'value': checkbox.value};
        request = $.ajax({
            url: "/removecookie.php",
            type: "post",
            data: serializedData
        });
        request.done(function (response, textStatus, jqXHR){
            console.log(response);
        });
    }
  })
  
});