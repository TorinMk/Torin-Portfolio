document.getElementById("contactForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const firstName = this.firstName.value.trim();
    const lastName = this.lastName.value.trim();
    const email = this.email.value.trim();
    const message = this.message.value.trim();

    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (firstName === "" || lastName === "") {
        alert("Please enter your first and last name!");
        return;
    }

    if (!pattern.test(email)) {
        alert("Please enter a valid email address");
        return;
    }

    if (message === "") {
        alert("Please enter a message");
        return;
    }

    alert("Form has been submitted!");
    this.reset();
});