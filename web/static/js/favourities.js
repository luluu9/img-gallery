var checkboxes = document.querySelectorAll(".rememberCheckbox");

checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
            var serializedData = { 'newFavourite': checkbox.value };
            request = $.ajax({
                url: "/favourite/set",
                type: "post",
                data: serializedData
            });
            request.done(function (response, textStatus, jqXHR) {
                console.log(response);
            });
        }
        else {
            var serializedData = { 'favourite': checkbox.value };
            request = $.ajax({
                url: "/favourite/unset",
                type: "post",
                data: serializedData
            });
            request.done(function (response, textStatus, jqXHR) {
                console.log(response);
            });
        }
    })

});