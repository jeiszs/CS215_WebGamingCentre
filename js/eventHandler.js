function validateEmail(email) {
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validateNickname(nick) {
    let nickRegex = /^[a-zA-Z0-9_]+$/;
    return nickRegex.test(nick);
}

function validatePWD(pwd) {
    let hasNonLetter = /[^a-zA-Z]/.test(pwd);
    if (pwd.length >= 6 && hasNonLetter) {
        return true;
    } else {
        return false;
    }
}


function validateLogin(event) {
    let email = document.getElementById("login-email");
    let password = document.getElementById("login-password");
    let formIsValid = true;

    if (email.value === "" || !validateEmail(email.value)) {
        email.classList.add("input-error");
        document.getElementById("email-error").classList.remove("hidden");
        formIsValid = false;
    } else {
        email.classList.remove("input-error");
        document.getElementById("email-error").classList.add("hidden");
    }

    if (password.value === "" || password.value.length < 6) {
        password.classList.add("input-error");
        document.getElementById("password-error").classList.remove("hidden");
        formIsValid = false;
    } else {
        password.classList.remove("input-error");
        document.getElementById("password-error").classList.add("hidden");
    }

    if (formIsValid === false) {
        event.preventDefault();
    }
}

function validateSignup(event) {
    let email = document.getElementById("signup-email");
    let nick = document.getElementById("signup-nickname");
    let pwd1 = document.getElementById("signup-password");
    let pwd2 = document.getElementById("signup-confirm");
    let formIsValid = true;

    if (email.value === "" || !validateEmail(email.value)) {
        email.classList.add("input-error");
        document.getElementById("signup-email-error").classList.remove("hidden");
        formIsValid = false;
    } else {
        email.classList.remove("input-error");
        document.getElementById("signup-email-error").classList.add("hidden");
    }

    if (!validateNickname(nick.value)) {
        nick.classList.add("input-error");
        document.getElementById("signup-nickname-error").classList.remove("hidden");
        formIsValid = false;
    } else {
        nick.classList.remove("input-error");
        document.getElementById("signup-nickname-error").classList.add("hidden");
    }

    if (!validatePWD(pwd1.value)) {
        pwd1.classList.add("input-error");
        document.getElementById("signup-password-error").classList.remove("hidden");
        formIsValid = false;
    } else {
        pwd1.classList.remove("input-error");
        document.getElementById("signup-password-error").classList.add("hidden");
    }

    if (pwd1.value !== pwd2.value || pwd2.value === "") {
        pwd2.classList.add("input-error");
        document.getElementById("confirm-error").classList.remove("hidden");
        formIsValid = false;
    } else {
        pwd2.classList.remove("input-error");
        document.getElementById("confirm-error").classList.add("hidden");
    }

    if (formIsValid === false) {
        event.preventDefault();
    }
}

document.addEventListener("DOMContentLoaded", function () {

    const nicknameInput = document.getElementById("nickname");
    const avatarInput = document.getElementById("userpfp");
    const dobInput = document.getElementById("dob");
    const saveButton = document.querySelector("#editables button:last-child");

    if (!nicknameInput || !avatarInput || !dobInput || !saveButton) return;

    function createErrorElement(input) {
        let error = input.parentNode.querySelector(".error-message");

        if (!error) {
            error = document.createElement("div");
            error.className = "error-message";
            error.style.color = "red";
            error.style.fontSize = "0.9em";
            input.parentNode.appendChild(error);
        }
        return error;
    }

    function showError(input, message) {
        input.classList.add("input-error");

        const errorElement = document.createElement("div");
        errorElement.className = "error-message";
        errorElement.style.color = "red";
        errorElement.style.fontSize = "0.9em";
        errorElement.textContent = message;

        input.parentNode.appendChild(errorElement);
    }

    function clearError(input) {
        input.classList.remove("input-error");

        const errors = input.parentNode.querySelectorAll(".error-message");
        errors.forEach(error => error.remove());
    }

    function validateProfileNickname() {
        const value = nicknameInput.value.trim();

        if (value === "") {
            showError(nicknameInput, "Nickname cannot be empty.");
            return false;
        }

        if (!validateNickname(value)) {
            showError(nicknameInput, "Nickname must contain only letters, numbers, and underscores.");
            return false;
        }

        clearError(nicknameInput);
        return true;
    }

    function validateAvatar() {
        if (avatarInput.files.length === 0) {
            showError(avatarInput, "Please select an avatar image.");
            return false;
        }

        const file = avatarInput.files[0];
        const validTypes = ["image/jpeg", "image/png", "image/gif"];

        if (!validTypes.includes(file.type)) {
            showError(avatarInput, "Invalid file type. Please select JPG, PNG, or GIF.");
            return false;
        }

        clearError(avatarInput);
        return true;
    }

    function validateDOB() {
        const value = dobInput.value;

        if (!value) {
            showError(dobInput, "Please select your date of birth.");
            return false;
        }

        const today = new Date();
        const dob = new Date(value);
        today.setHours(0, 0, 0, 0);

        if (isNaN(dob.getTime()) || dob >= today) {
            showError(dobInput, "Date of birth must be a valid past date.");
            return false;
        }

        clearError(dobInput);
        return true;
    }

    function showFormMessage(message, type = "success") {
        let messageBox = document.getElementById("form-message");

        if (!messageBox) {
            messageBox = document.createElement("div");
            messageBox.id = "form-message";
            messageBox.style.marginTop = "10px";
            document.getElementById("editables").appendChild(messageBox);
        }

        messageBox.textContent = message;
        messageBox.style.color = type === "success" ? "green" : "red";
    }

    nicknameInput.addEventListener("input", validateProfileNickname);
    avatarInput.addEventListener("change", validateAvatar);
    dobInput.addEventListener("change", validateDOB);

    saveButton.addEventListener("click", function () {
        const isValid =
            validateProfileNickname() &&
            validateAvatar() &&
            validateDOB();

        if (isValid) {
            showFormMessage("Profile information saved successfully!", "success");
        } else {
            showFormMessage("Please correct the errors before saving.", "error");
        }
    });
});
