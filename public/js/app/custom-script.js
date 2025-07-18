const template = `
Livewire.on('copyPasswordToClipboard', (event) => {
    const password = Array.isArray(event) ? event[0].password : null;
    if (password) {
        navigator.clipboard.writeText(password);

        new FilamentNotification()
            .title('Password Copied')
            .body('The password has been copied to your clipboard.')
            .success()
            .send();
    } else {
        new FilamentNotification()
            .title('Error')
            .body('No password to copy.')
            .danger()
            .send();
    }
});
`;

// Add before the closing </body> tag
document.addEventListener("DOMContentLoaded", () => {
    const script = document.createElement("script");
    script.textContent = template;
    document.body.appendChild(script);
});
