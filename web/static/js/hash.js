var shajs = require('sha/sha.js')
var form = document.getElementById('hashForm');

form.onsubmit = function (e) {
    var passwordInput = document.getElementById('hashForm');
    var hashedPassword = shajs('sha256').update(passwordInput.value).digest('hex')
    passwordInput.value = hashedPassword;
};