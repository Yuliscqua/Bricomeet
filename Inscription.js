document.addEventListener('DOMContentLoaded', function () {
    var pseudoInput = document.getElementById('pseudo');
    var feedback = document.getElementById('pseudo-feedback');

    pseudoInput.addEventListener('input', function () {
        var pseudo = pseudoInput.value.trim();
        if (pseudo !== '') {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Inscription.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    feedback.textContent = xhr.responseText;
                }
            };
            xhr.send('pseudo=' + encodeURIComponent(pseudo));
        } else {
            feedback.textContent = '';
        }
    });
});
