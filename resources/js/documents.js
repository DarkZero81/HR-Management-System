function updateFileName(input) {
    const fileNameDisplay = document.getElementById('fileName');
    if (input.files && input.files.length > 0) {
        fileNameDisplay.textContent = input.files[0].name;
        fileNameDisplay.classList.remove('hidden');
    } else {
        fileNameDisplay.classList.add('hidden');
    }
}
