document.getElementById("contactForm").addEventListener("submit", function (e) {

    const firstName = this.fname.value.trim();
    const lastName = this.lname.value.trim();
    const email = this.email.value.trim();
    const subject = this.subject.value.trim();
    const message = this.message.value.trim();

    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (firstName === "" || lastName === "") {
        e.preventDefault();
        alert("Please enter your first and last name!");
        return;
    }

    if (!pattern.test(email)) {
        e.preventDefault();
        alert("Please enter a valid email address");
        return;
    }

    if (subject === "") {
        e.preventDefault();
        alert("Please enter a subject");
        return;
    }

    if (message === "") {
        e.preventDefault();
        alert("Please enter a message");
        return;
    }
});