document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    form.addEventListener("submit", (e) => {
        if (!email.value.includes("@")) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }

        if (password.value.trim() === "") {
            alert("Password cannot be empty.");
            e.preventDefault();
        }
    });
});
