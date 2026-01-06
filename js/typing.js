const text = "My Name is Torin Mallett";
const typingSpeed = 100;

const element = document.getElementById("typing-text");
let i = 0;

function type() {
    if (i < text.length) {
        element.innerHTML += text.charAt(i);
        i++;
        setTimeout(type, typingSpeed);
    }
}

setTimeout(type, 500);