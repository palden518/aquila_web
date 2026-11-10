document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll(
        "[data-reservation-form], [data-catering-form], [data-contact-form]",
    );

    forms.forEach(function (form) {
        // Use inline feedback instead of browser validation popups.
        form.noValidate = true;

        const fields = form.querySelectorAll(
            'input:not([type="hidden"]):not([type="submit"]):not([name="website"]), select, textarea',
        );

        function getFeedback(field) {
            let feedback = field.nextElementSibling;

            if (!feedback || !feedback.classList.contains("invalid-feedback")) {
                feedback = document.createElement("div");
                feedback.className = "invalid-feedback";
                feedback.dataset.clientValidation = "true";
                field.insertAdjacentElement("afterend", feedback);
            }

            if (!feedback.id) {
                feedback.id = field.id
                    ? field.id + "-client-error"
                    : field.name + "-client-error";
            }

            return feedback;
        }

        function validateField(field) {
            if (field.disabled || !field.name) {
                return true;
            }

            const valid = field.checkValidity();
            const feedback = getFeedback(field);
            const serverError = feedback.dataset.serverError === "true";

            if (!valid) {
                field.classList.add("is-invalid");
                field.classList.remove("is-valid");

                if (!serverError) {
                    feedback.textContent = field.validationMessage;
                }

                feedback.style.display = "block";
                field.setAttribute("aria-invalid", "true");
                field.setAttribute("aria-describedby", feedback.id);

                return false;
            }

            if (serverError) {
                // Keep Laravel's error until the user edits the field.
                return true;
            }

            field.classList.remove("is-invalid");

            // Do not mark optional empty fields as valid.
            if (field.value.trim() !== "") {
                field.classList.add("is-valid");
            } else {
                field.classList.remove("is-valid");
            }

            feedback.textContent = "";
            feedback.style.display = "none";

            field.removeAttribute("aria-invalid");
            field.removeAttribute("aria-describedby");

            return true;
        }

        fields.forEach(function (field) {
            const feedback = field.nextElementSibling;

            if (
                feedback &&
                feedback.classList.contains("invalid-feedback") &&
                field.classList.contains("is-invalid")
            ) {
                feedback.dataset.serverError = "true";
            }

            field.addEventListener("blur", function () {
                validateField(field);
            });

            function handleChange() {
                const feedback = field.nextElementSibling;

                if (feedback) {
                    delete feedback.dataset.serverError;
                }

                validateField(field);
            }

            field.addEventListener("input", handleChange);
            field.addEventListener("change", handleChange);
        });

        form.addEventListener("submit", function (event) {
            let firstInvalid = null;

            fields.forEach(function (field) {
                if (!validateField(field) && !firstInvalid) {
                    firstInvalid = field;
                }
            });

            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.focus();
            }
        });
    });
});
