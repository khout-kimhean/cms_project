function submitForm(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = document.getElementById("uploadForm");
    const fileInput = document.getElementById("file");
    const uploadButton = document.getElementById("uploadButton");
    const uploadMessage = document.getElementById("uploadMessage");

    uploadMessage.innerHTML = ""; // Clear any previous message
    uploadButton.disabled = true; // Disable the submit button

    const formData = new FormData(form);

    fetch("upload.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                uploadMessage.innerHTML = '<div class="success-message">' + data.message + '</div>';
                setTimeout(() => {
                    uploadMessage.innerHTML = ''; // Clear the message after a few seconds
                }, 5000); // Adjust the time (in milliseconds) as needed
            } else {
                uploadMessage.innerHTML = '<div class="error-message">' + data.message + '</div>';
            }
        })
        .catch((error) => {
            uploadMessage.innerHTML = '<div class="error-message">Error: ' + error + '</div>';
        })
        .finally(() => {
            uploadButton.disabled = false; // Re-enable the submit button after processing
        });
}
