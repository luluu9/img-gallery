var req = null;

function search(value) {
    if (!value) return;
    if (req != null) req.abort();
    req = $.ajax({
        type: "POST",
        url: "search",
        data: { 'name': value },
        success: function (msg) {
            console.log(msg);
            var resultDiv = document.getElementById("searchResult");
            var matchingImages = JSON.parse(msg);
            console.log(matchingImages);
            var newElements = []

            matchingImages.forEach(image => {
                var img = document.createElement('img');
                img.src = "images/miniature_" + image;
                newElements.push(img);
            })
            resultDiv.replaceChildren(...newElements);
        }
    });
}